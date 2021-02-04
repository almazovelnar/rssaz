<?php

namespace core\forms\auth\backend;

/**
 * Login form
 */
class LoginForm extends \core\forms\auth\LoginForm
{
    /**
     * @var bool
     */
    public $rememberMe = true;

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'username'   => 'İstifadəçi adı',
            'password'   => 'Şifrə',
            'rememberMe' => 'Məni xatırla',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
        ];
    }
}
