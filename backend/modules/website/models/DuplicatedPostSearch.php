<?php

namespace backend\modules\website\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Customer\Website\{PostDuplicateReason};

/**
 * Class RemovedPostSearch
 * @package backend\modules\website\models
 */
class DuplicatedPostSearch extends Model
{
    public ?string $reason = null;
    public ?string $createdAt = null;

    public function rules(): array
    {
        return [
            [['reason', 'createdAt'], 'safe'],
            ['createdAt', 'date', 'format' => 'php:Y-m-d']
        ];
    }

    public function search($params)
    {
        $query = PostDuplicateReason::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC],
                'attributes' => ['id', 'created_at'],
            ]
        ]);

        $this->load($params);
        if (!$this->validate()) return $dataProvider;

        if (!empty($this->createdAt))
            $query->andWhere(['DATE(created_at)' => $this->createdAt]);

        if ($this->reason)
            $query->andWhere(['like', 'reason', $this->reason]);

        return $dataProvider;
    }
}