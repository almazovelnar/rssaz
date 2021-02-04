<?php

namespace core\entities\traits;

use Yii;
use yii\base\NotSupportedException;

trait Identity
{
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::$activeStatus]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        unset($token, $type);
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }
}