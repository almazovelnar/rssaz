<?php

namespace core\entities\Page;

use core\entities\Meta;
use yii\db\ActiveRecord;
use core\behaviors\MetaBehavior;

/**
 * Class Translation
 *
 * @package core\entities\Page
 * @property int $id
 * @property int $page_id
 * @property string $title
 * @property string $description
 * @property Meta $meta
 */
class Translation extends ActiveRecord
{
    public $meta;

    /**
     * @return string
     */
    public static function tableName()
    {
        return '{{%pages_lang}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'meta' => [
                'class' => MetaBehavior::class,
                'attribute' => 'meta',
                'jsonAttribute' => 'meta_json',
            ],
        ];
    }
}