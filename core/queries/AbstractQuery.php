<?php

namespace core\queries;

use Yii;
use yii\helpers\Inflector;
use yii\base\InvalidConfigException;
use core\exceptions\NotFoundException;
use core\exceptions\ScopeDoesntExistException;
use kak\clickhouse\{ActiveQuery, ActiveRecord};

/**
 * Class Query
 * @package core\queries
 */
abstract class AbstractQuery extends ActiveQuery
{
    /**
     * @return ActiveRecord|null
     * @throws InvalidConfigException
     */
    public function first()
    {
        return parent::one();
    }

    /**
     * @return ActiveRecord
     * @throws NotFoundException
     * @throws InvalidConfigException
     */
    public function firstOrFail()
    {
        $result = parent::one();
        if (empty($result))
            throw new NotFoundException('Record not found.');
        return $result;
    }

    public function get()
    {
        return parent::all();
    }

    public function updateRecord(
        string $table,
        array $fields,
        array $condition
    ): bool
    {
        if (empty($fields)) return false;

        $sql = "ALTER table {$table} UPDATE ";
        foreach ($fields as $column => $value) {
            $param = !is_callable($value) ? ':'.$column : $value();
            $sql .= "{$column} = {$param}, ";
        }
        $sql = rtrim($sql, ', ');
        $sql .= ' WHERE ';

        $i = 0;
        $sizeOfCondition = count($condition) - 1;
        foreach ($condition as $column => $value) {
            $sql .= "({$column} = {$value})";
            if ($i < $sizeOfCondition) {
                $sql .= ' AND ';
                $i++;
            }
        }

        $query = Yii::$app->clickhouse->createCommand($sql);
        foreach ($fields as $column => $value)
            $query->bindValue(':'. $column, $value);
        return $query->execute()->getIsOk();
    }

    public function deleteRecord(string $table, array $conditions): bool
    {
        $params = [];
        $clickhouse = Yii::$app->clickhouse;
        $sql = "ALTER TABLE {$table} DELETE WHERE ";

        $i = 0;
        $sizeOfCondition = count($conditions) - 1;
        foreach ($conditions as $key => $condition) {
            $sql .=  "(" . $clickhouse->getQueryBuilder()->buildCondition([$key => $condition], $params) . ")";
            if ($i < $sizeOfCondition) {
                $sql .= ' AND ';
                $i++;
            }
        }

        return $clickhouse->createCommand($sql, $params)->execute()->getIsOk();
    }

    /**
     * Method for calling scope dynamically.
     *
     * @param string $name
     * @param array $params
     * @return mixed|void
     * @throws ScopeDoesntExistException
     */
    public function __call($name, $params)
    {
        $method = 'scope' . Inflector::camelize($name);
        if (!method_exists($this, $method))
            throw new ScopeDoesntExistException("Method ({$method}) not found in scopes.");
        return call_user_func_array([$this, $method], [$this, ...$params]);
    }

    /**
     * Method for filtering records.
     *
     * @param array $filters
     * @return AbstractQuery
     */
    public function filter(array $filters = [])
    {
        foreach ($filters as $filter => $value) {
            $method = 'filterBy' . ucfirst($filter);
            if (!method_exists($this, $method)) continue;
            call_user_func([$this, $method], $this,
                is_string($value) ? htmlspecialchars($value) : $value
            );
        }
        return $this;
    }
}