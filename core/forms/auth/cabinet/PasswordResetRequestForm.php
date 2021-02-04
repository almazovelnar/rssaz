<?php

namespace core\forms\auth\cabinet;

use core\entities\Customer\Customer;
use Yii;
use yii\base\Model;

class PasswordResetRequestForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            [
                'email',
                'exist',
                'targetClass' => Customer::class,
                'filter' => ['status' => Customer::STATUS_ACTIVE],
                'message' => 'There is no user with this email address.'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('form_labels', 'email'),
        ];
    }
}
