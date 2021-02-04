<?php

namespace backend\modules\antifraud\models;

use yii\base\Model;
use kak\clickhouse\Query;
use kak\clickhouse\data\SqlDataProvider;
use core\entities\Customer\Website\Website;

class TopIPSearch extends Model
{
    const TYPE_VIEWS = 'views';
    const TYPE_CLICKS = 'clicks';

    public $website;
    public $date;
    public $type = self::TYPE_VIEWS;

    public function formName()
    {
        return '';
    }

    public static function types(): array
    {
        return [
            self::TYPE_VIEWS => 'Views',
            self::TYPE_CLICKS => 'Clicks',
        ];
    }

    public function rules()
    {
        return [
            ['website', 'integer'],
            ['type', 'in', 'range' => array_keys(self::types())],
            ['date', 'date', 'format' => 'php: Y-m-d'],
        ];
    }

    public function search(array $params = [])
    {
        $this->load($params);

        if (!$this->date)
            $this->date = date('Y-m-d');

        $query = $this->getQueryByType($this->type, $this->date);

        if (!$this->validate()) return new SqlDataProvider([
            'db' => 'clickhouse',
            'sql' => $query->createCommand()->getRawSql(),
            'pagination' => ['pageSize' => 50],
        ]);


        if ($this->website)
            $query->andWhere(['s.website_id' => (int) $this->website]);


        return new SqlDataProvider([
            'db' => 'clickhouse',
            'sql' => $query->createCommand()->getRawSql(),
            'pagination' => ['pageSize' => 50],
        ]);
    }

    private function getQueryByType(string $type, string $date): Query
    {
        $query = (new Query())
            ->select('s.ip, s.website_id')
            ->from('sessions s')
            ->groupBy('s.ip, s.website_id');

        if ($type == self::TYPE_VIEWS) {
            $query->addSelect('COUNT(id) as views')->orderBy('views DESC')
                ->andWhere('toDate(s.created_at) = :date', [':date' => $date]);
        } else if ($type == self::TYPE_CLICKS) {
            $query->addSelect('COUNT(sc.post_id) as clicks')
                ->leftJoin('session_clicks sc', 's.id = sc.session_id')
                ->orderBy('clicks DESC')
                ->andWhere('toDate(sc.created_at) = :date', [':date' => $date]);
        }

        return $query;
    }
}