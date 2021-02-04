<?php

namespace core\forms\manager\User;

use yii\base\Model;
use core\entities\User;

/**
 * Class CreateForm
 *
 * @package core\forms\manager\User
 */
class CreateForm extends Model
{
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $password;
    /**
     * @var string
     */
    public $repeatPassword;
    /**
     * @var string
     */
    public $role;
    /**
     * @var int
     */
    public $status;

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'password', 'repeatPassword', 'role', 'status'], 'required'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 30],
            [['email'], 'trim'],
            ['email', 'unique', 'targetClass' => User::class],
            ['status', 'integer'],
            ['password', 'string', 'min' => 6],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password', 'message' => 'Passwords don`t match' ],
        ];
    }
}