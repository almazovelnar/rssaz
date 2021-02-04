<?php

namespace backend\modules\statistics\models;

use yii\base\Model;
use RuntimeException;
use yii\db\Expression;
use core\entities\Parse\Parse;
use yii\data\ActiveDataProvider;
use core\helpers\DiagnosticsHelper;

/**
 * Class StatisticsViewSearch
 * @package backend\modules\statistics\models
 */
class StatisticsViewSearch extends Model
{
    public ?string $date = null;
    public ?string $website = null;
    public ?string $status = null;

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            [['status', 'website'], 'required'],
            ['website', 'integer'],
            ['date', 'date', 'format' => 'php:Y-m-d'],
            ['status', 'in', 'range' => array_keys(DiagnosticsHelper::statusesList())]
        ];
    }

    public function search(array $params = [])
    {
        $query = Parse::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => [
                    'created_at',
                    'elapsed_time'
                ],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) throw new RuntimeException();

        if ($this->date)
            $query->andWhere(new Expression('DATE(created_at) = :date', [':date' => $this->date]));

        if ($this->status != null) {
            $query->andWhere(['status' => $this->status]);
        }

        if ($this->website) {
            $query->andWhere(['website_id' => (int) $this->website]);
        }

        return $dataProvider;
    }
}