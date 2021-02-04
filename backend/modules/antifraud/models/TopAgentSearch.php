<?php

namespace backend\modules\antifraud\models;

use yii\base\Model;
use kak\clickhouse\Query;
use kak\clickhouse\data\SqlDataProvider;
use core\entities\Customer\Website\Website;

class TopAgentSearch extends Model
{
    const TYPE_VIEWS = 'views';
    const TYPE_CLICKS = 'clicks';

    public $website;
    public $agent;
    public $date;
    public $ip;
    public $type = self::TYPE_VIEWS;

    public function rules()
    {
        return [
            ['website', 'integer'],
            ['date', 'date', 'format' => 'php: Y-m-d'],
            ['type', 'in', 'range' => array_keys(self::types())],
            ['ip', 'ip', 'ipv6' => false],
            ['agent', 'string']
        ];
    }

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

        if (!empty($this->ip))
            $query->andWhere('s.ip = :ip', [':ip' => $this->ip]);

        if ($this->agent)
            $query->andWhere(['like', 'agent', $this->agent]);

        return new SqlDataProvider([
            'db' => 'clickhouse',
            'sql' => $query->createCommand()->getRawSql(),
            'pagination' => ['pageSize' => 50],
        ]);
    }

    private function getQueryByType(string $type, string $date): Query
    {
        $query = (new Query())
            ->select('s.agent, s.website_id')
            ->from('sessions s')
            ->groupBy('s.agent, s.website_id');

        if ($type == self::TYPE_VIEWS) {
            $query->addSelect('COUNT(id) as views')
                ->orderBy('views DESC')
                ->andWhere('toDate(s.created_at) = :date', [':date' => $date]);
        } else if ($type == self::TYPE_CLICKS) {
            $query->addSelect('COUNT(sc.post_id) as clicks')
                ->innerJoin('session_clicks sc', 's.id = sc.session_id')
                ->orderBy('clicks DESC')
                ->andWhere('toDate(sc.created_at) = :date', [':date' => $date]);
        }

        return $query;
    }
}