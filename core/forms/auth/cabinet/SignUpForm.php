<?php

namespace core\forms\auth\cabinet;

use Yii;
use yii\base\Model;
use yii\helpers\Html;
use core\entities\Customer\Customer;
use himiklab\yii2\recaptcha\ReCaptchaValidator3;

class SignUpForm extends Model
{
    public ?string $name = null;
    public ?string $surname = null;
    public ?string $email = null;
    public ?string $password = null;
    public ?string $passwordRepeat = null;
    public ?string $reCaptcha = null;
    public ?array $sitesList = null;
    public ?int $agree = null;

    public function rules()
    {
        return [
            [['name', 'surname'], 'trim'],
            [['name', 'surname'], 'required'],
            [['name', 'surname'], 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => Customer::class, 'message' => Yii::t('form_validation', 'email')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['passwordRepeat', 'required'],
            ['passwordRepeat', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('form_validation', 'passwordRepeat')],

            ['reCaptcha', ReCaptchaValidator3::class],

            ['sitesList', 'each', 'rule' => ['string']],

            ['agree', 'required', 'requiredValue' => 1, 'message' => Yii::t('form_validation', 'agree')],
        ];
    }

    public function attributeLabels()
    {
        return [
            'email' => Yii::t('form_labels', 'email'),
            'name' => Yii::t('form_labels', 'name'),
            'surname' => Yii::t('form_labels', 'surname'),
            'password' => Yii::t('form_labels', 'password'),
            'passwordRepeat' => Yii::t('form_labels', 'passwordRepeat'),
            'sitesList' => Yii::t('form_labels', 'sitesList'),
            'agree' => Yii::t('form_labels', 'agree', [
                'link' => Html::a(
                    Yii::t('form_labels', 'agree_link'),
                    Yii::$app->frontendUrlManager->createAbsoluteUrl(['privacy-policy']),
                    ['target' => '_blank']
                ),
            ])
        ];
    }
}
