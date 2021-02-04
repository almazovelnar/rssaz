<?php

namespace core\entities\traits;

use Yii;

trait SignUpConfirm
{
    public function confirmSignUp()
    {
        $this->email_confirm_token = null;
        $this->status = self::STATUS_ACTIVE;
    }

    public function generateEmailConfirmToken()
    {
        $this->email_confirm_token = Yii::$app->security->generateRandomString();
    }

    public function removeEmailConfirmToken()
    {
        $this->email_confirm_token = null;
    }
}