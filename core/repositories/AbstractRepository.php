<?php

namespace core\repositories;

use RuntimeException;
use yii\db\ActiveRecord;

/**
 * Class AbstractRepository
 * @package core\repositories
 */
abstract class AbstractRepository
{
    /**
     * @param array $condition
     */
    abstract protected function getBy(array $condition);

    /**
     * @param int $id
     * @return array|null|\yii\db\ActiveRecord
     */
    public function get(int $id)
    {
        return $this->getBy(['id' => $id]);
    }

    /**
     * @param ActiveRecord $record
     */
    public function save(ActiveRecord $record)
    {
        if (!$record->save()) {
            throw new RuntimeException("Can't save record.");
        }
    }

    /**
     * @param ActiveRecord $record
     * @throws \Throwable
     */
    public function remove(ActiveRecord $record)
    {
        if (!$record->delete()) {
            throw new RuntimeException('Deleting error.');
        }
    }
}