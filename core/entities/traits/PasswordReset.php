<?php

namespace core\entities\traits;

use Yii;

trait PasswordReset
{
    public function resetPassword($password)
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Password resetting was not requested yet.');
        }
        $this->setPassword($password);
        $this->removePasswordResetToken();
    }

    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    private function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }
}