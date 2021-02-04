<?php

namespace backend\modules\category\helpers;

use core\entities\Category\Category;
use yii\helpers\ArrayHelper;

class CategoryHelper
{
    public static function getList()
    {
        return ArrayHelper::map(
            Category::find()->orderBy('lft')->asArray()->all(), 'id', function (array $category) {
            return ($category['depth'] > 1 ? str_repeat('-- ', $category['depth'] - 1) . ' ' : '') . $category['name'];
        });
    }
}