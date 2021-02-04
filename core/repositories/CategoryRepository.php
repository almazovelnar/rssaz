<?php

namespace core\repositories;

use core\entities\Category\Category;
use core\exceptions\NotFoundException;
use yii\db\ActiveQuery;

class CategoryRepository extends AbstractRepository
{
    /**
     * @return array|null|\yii\db\ActiveRecord
     * @throws NotFoundException
     */
    public function getRoot()
    {
        return $this->getBy(['depth' => 0]);
    }

    /**
     * @param $language
     * @param $title
     * @return Category
     * @throws NotFoundException
     */
    public function getByTitle($language, $title)
    {
        /** @var Category $category */
        $category = Category::find()->joinWith(['translations t' => function (ActiveQuery $query) use ($language) {
            return $query->andOnCondition(['language' => $language]);
        }])->andWhere(['t.title' => $title, 'status' => true])->limit(1)->one();

        if (!$category) {
            throw new NotFoundException('Category with title: ' . $title . ' not found !');
        }

        return $category;
    }

    public function getAllByLanguage($language)
    {
        return Category::find()->with(['multilingual' => function (ActiveQuery $query) use ($language) {
            return $query->andOnCondition(['language' => $language]);
        }])->andWhere(['status' => true])->andWhere(['>', 'depth', 0])->all();
    }

    /**
     * @param array $condition
     * @return Category
     * @throws NotFoundException
     */
    protected function getBy(array $condition): Category
    {
        if (!($category = Category::find()->andWhere($condition)->limit(1)->one())) {
            throw new NotFoundException('Category not found !');
        }
        /** @var Category $category */
        return $category;
    }
}