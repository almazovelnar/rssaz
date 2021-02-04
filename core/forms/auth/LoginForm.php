<?php

namespace core\forms\auth;

use yii\base\Model;

/**
 * Class LoginForm
 *
 * @package core\forms\auth
 * @property bool rememberMe
 */
abstract class LoginForm extends Model
{
    public $username;
    public $password;
}