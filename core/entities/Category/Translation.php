<?php

namespace core\entities\Category;

use core\behaviors\MetaBehavior;
use core\entities\Meta;
use yii\db\ActiveRecord;

/**
 * Class Translation
 * @package core\entities\Category
 * @property int $id
 * @property string $title
 * @property Meta $meta
 */
class Translation extends ActiveRecord
{
    public $meta;

    public static function tableName()
    {
        return '{{%categories_lang}}';
    }

    public function behaviors()
    {
        return [
            'meta' => ['class' => MetaBehavior::class],
        ];
    }
}