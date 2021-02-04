<?php

namespace core\forms\cabinet\Website;

use Yii;
use yii\base\Model;

/**
 * Class SelectWebsiteForm
 * @package core\forms\cabinet\Website
 */
class SelectWebsiteForm extends Model
{
    public ?int $website = null;

    public function attributeLabels(): array
    {
        return [
            'website' => Yii::t('code', 'choose_site'),
        ];
    }

    public function rules(): array
    {
        return [
            ['website', 'required'],
            ['website', 'integer'],
        ];
    }
}