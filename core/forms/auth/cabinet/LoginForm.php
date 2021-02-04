<?php

namespace core\forms\auth\cabinet;

use Yii;
use himiklab\yii2\recaptcha\ReCaptchaValidator3;

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
     * @var string
     */
    public $reCaptcha;

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'username'   => Yii::t('form_labels', 'email'),
            'password'   => Yii::t('form_labels', 'password'),
            'rememberMe' => Yii::t('form_labels', 'rememberMe'),
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
            ['reCaptcha', ReCaptchaValidator3::class],
        ];
    }
}
