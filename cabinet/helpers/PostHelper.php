<?php

namespace cabinet\helpers;

use cabinet\models\PostSearch;
use core\entities\Customer\Website\Website;
use yii\helpers\ArrayHelper;

class PostHelper
{
    public static function rangeList()
    {
        $list = [];
        foreach (PostSearch::RANGES as $range) {
            $list[$range] = in_array($range, [0, 1])
                ? \Yii::t('news', 'range_' . $range)
                : \Yii::t('news', 'range_{number}', ['number' => $range]);
        }

        return $list;
    }

    public static function websitesList()
    {
        return ArrayHelper::map(
            Website::find()->select(['id', 'name'])->where(['customer_id' => \Yii::$app->user->id, 'status' => Website::STATUS_ACTIVE])->asArray()->all(),
            'id',
            'name'
        );
    }

    public static function priorityList()
    {
        return ['Sadə xəbərlər', 'Prioritetli xəbərlər'];
    }

    public static function showCountList()
    {
        $list = [];
        foreach (PostSearch::LIMITS as $limit) {
            $list[$limit] = $limit;
        }

        return $list;
    }
}