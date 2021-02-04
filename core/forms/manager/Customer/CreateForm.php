<?php

namespace core\forms\manager\Customer;

use core\entities\Customer\Customer;
use core\helpers\CustomerHelper;
use yii\base\Model;

class CreateForm extends Model
{
    public $name;
    public $surname;
    public $email;
    public $password;
    public $repeatPassword;
    public $thumbFile;
    public $status;

    public function rules()
    {
        return [
            [['name', 'surname', 'email', 'password', 'repeatPassword', 'status'], 'required'],
            [['email', 'name', 'surname'], 'string', 'max' => 30],
            [['email'], 'email'],
            [['email'], 'trim'],
            ['email', 'unique', 'targetClass' => Customer::class],
            ['status', 'in', 'range' => array_keys(CustomerHelper::statusesList())],
            ['password', 'string', 'min' => 6],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password'],
            ['thumbFile', 'image']
        ];
    }
}