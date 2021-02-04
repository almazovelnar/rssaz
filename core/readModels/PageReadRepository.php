<?php

namespace core\readModels;

use yii\db\ActiveQuery;
use core\entities\Page\Page;

class PageReadRepository extends ReadRepository
{
    public function getOnTop()
    {
        return Page::find()->with(['multilingual' => function (ActiveQuery $query) {
            return $query->andOnCondition(['language' => $this->language]);
        }])->where(['status' => true, 'show_on_top' => true])->all();
    }

    public function getBySlug($slug)
    {
        return Page::find()->with(['multilingual' => function (ActiveQuery $query) {
            return $query->andOnCondition(['language' => $this->language]);
        }])->where(['status' => true, 'slug' => $slug])->limit(1)->one();
    }

    public function getPageBySlugWithTranslations($slug)
    {
        return Page::find()->with('translations')->where(['status' => true, 'slug' => $slug])->limit(1)->one();
    }

    public function getPages(string $type, int $limit)
    {
        return Page::find()->with(['multilingual' => function (ActiveQuery $query) {
            return $query->andOnCondition(['language' => $this->language]);
        }])->where(['status' => 1, 'show' => 1, 'type' => $type])->limit($limit)->all();
    }
}