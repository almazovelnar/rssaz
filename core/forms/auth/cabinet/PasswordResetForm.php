<?php

namespace core\forms\auth\cabinet;

use Yii;
use yii\base\Model;

class PasswordResetForm extends Model
{
    public $password;
    public $passwordRepeat;

    public function rules()
    {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['passwordRepeat', 'required'],
            ['passwordRepeat', 'compare', 'compareAttribute'=>'password'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('form_labels', 'password'),
            'passwordRepeat' => Yii::t('form_labels', 'passwordRepeat'),
        ];
    }
}
