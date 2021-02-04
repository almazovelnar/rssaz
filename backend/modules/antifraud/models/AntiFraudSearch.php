<?php

namespace backend\modules\antifraud\models;

use yii\base\Model;
use kak\clickhouse\Query;
use kak\clickhouse\data\SqlDataProvider;
use core\entities\Customer\Website\Website;

class AntiFraudSearch extends Model
{
    public $website;
    public $date;
    public $ip;

    public function rules()
    {
        return [
            ['website', 'integer'],
            ['date', 'date', 'format' => 'php: Y-m-d'],
            ['ip', 'ip', 'ipv6' => false]
        ];
    }

    public function formName()
    {
        return '';
    }

    public function search(array $params = [])
    {
        $query = (new Query())
            ->select("s.website_id, s.ip, s.agent, s.created_at, length(s.posts) as post_count")
            ->from('sessions s')
            ->orderBy('s.created_at DESC');

        $this->load($params);

        if (!$this->validate()) return new SqlDataProvider([
            'db' => 'clickhouse',
            'sql' => $query->createCommand()->getRawSql(),
            'pagination' => ['pageSize' => 50],
        ]);

        if (!$this->date)
            $this->date = date('Y-m-d');

        if ($this->website)
            $query->andWhere(['s.website_id' => (int) $this->website]);

        if (!empty($this->ip))
            $query->andWhere('s.ip = :ip', [':ip' => trim($this->ip)]);

        $query->andWhere('toDate(s.created_at) = :date', [':date' => $this->date]);
        return new SqlDataProvider([
            'db' => 'clickhouse',
            'sql' => $query->createCommand()->getRawSql(),
            'pagination' => ['pageSize' => 50],
        ]);
    }
}