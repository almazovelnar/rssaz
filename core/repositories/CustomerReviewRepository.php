<?php

namespace core\repositories;

use core\exceptions\NotFoundException;
use core\entities\Customer\Review\Review;
use Yii;
use yii\db\ActiveQuery;

/**
 * Class CustomerReviewRepository
 * @package core\repositories
 */
class CustomerReviewRepository extends AbstractRepository
{
    protected string $language;

    public function __construct()
    {
        $this->language = Yii::$app->language;
    }

    /**
     * @param array $condition
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundException
     */
    protected function getBy(array $condition)
    {
        if (!($review = Review::find()->andWhere($condition)->limit(1)->one())) {
            throw new NotFoundException('Review not found !');
        }

        return $review;
    }

    public function all()
    {
        return Review::find()->where(['status' => 1])->joinWith(['multilingual' => function (ActiveQuery $query) {
            return $query->andOnCondition(['language' => $this->language])->alias('l');
        }])->andWhere(['not', ['l.review' => '']])->asArray()->all();
    }
}