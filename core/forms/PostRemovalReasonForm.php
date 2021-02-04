<?php

namespace core\forms;

use yii\base\Model;

/**
 * Class PostRemovalReasonForm
 * @package core\forms
 */
class PostRemovalReasonForm extends Model
{
    public ?string $reason;

    public function formName(): string
    {
        return '';
    }

    public function rules()
    {
        return [
            [['reason'], 'required'],
            [['reason'], 'string'],
        ];
    }
}