<?php

namespace core\entities\Customer\Review;

use yii\db\ActiveRecord;

/**
 * Class Translation
 *
 * @package core\entities\Customer\Review
 * @property int $id
 * @property int $review_id
 * @property string $review
 */
class Translation extends ActiveRecord
{
    public static function tableName(): string
    {
        return '{{%customer_reviews_lang}}';
    }
}