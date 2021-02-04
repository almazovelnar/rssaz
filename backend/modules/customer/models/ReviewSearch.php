<?php

namespace backend\modules\customer\models;

use Yii;
use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use core\entities\Customer\Review\Review;

/**
 * ReviewSearch represents the model behind the search form of `core\entities\Customer\Review\Review`.
 */
class ReviewSearch extends Review
{
    public ?string $review = null;
    public ?int $status = null;

    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['review', 'status'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = Review::find()->alias('r')->joinWith(['translations t' => function (ActiveQuery $query) {
            return $query->andOnCondition(['language' => Yii::$app->language]);
        }]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['like', 't.review', $this->review]);

        return $dataProvider;
    }
}
