<?php

namespace core\readModels;

use yii\db\Expression;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use core\entities\Category\Category;

class CategoryReadRepository extends ReadRepository
{
    public function getAll(array $params = [], array $ordering = [])
    {
        return Category::find()
            ->with(['multilingual' => function (ActiveQuery $query) {
                return $query->andOnCondition(['language' => $this->language]);
            }])
            ->where(['status' => true, 'depth' => 1])
            ->andWhere($params)
            ->orderBy($ordering ?: ['lft' => SORT_ASC])
            ->all();
    }

    public function getMenu()
    {
        return Category::find()->with(['multilingual' => function (ActiveQuery $query) {
            return $query->andOnCondition(['language' => $this->language]);
        }])->where(['status' => true, 'depth' => 1, 'show_in_menu' => true])->orderBy('lft')->all();
    }

    /**
     * @param $slug
     * @return null|ActiveRecord|Category
     */
    public function getBySlug($slug)
    {
        return Category::find()->with(['multilingual' => function (ActiveQuery $query) {
            return $query->andOnCondition(['language' => $this->language]);
        }])->where(['status' => true, 'slug' => $slug])->limit(1)->one();
    }
}