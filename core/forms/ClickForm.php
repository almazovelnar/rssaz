<?php

namespace core\forms;

use yii\base\Model;

/**
 * Class ClickForm
 * @package core\forms
 */
class ClickForm extends Model
{
    public int $id;
    public ?string $sid = null;

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            [['id', 'sid'], 'required'],
            [['sid'], 'thamtech\uuid\validators\UuidValidator'],
            [['id'], 'number'],
        ];
    }
}