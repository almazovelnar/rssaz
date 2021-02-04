<?php

namespace core\entities;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use backend\components\auth\Rbac;
use core\entities\traits\Identity;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property bool $status
 * @property integer $role
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    use Identity;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_MODERATOR = 'moderator';

    public static $activeStatus = true;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%users}}';
    }

    /**
     * @param string $email
     * @param string $username
     * @param string $password
     * @param string $role
     * @param bool $status
     * @return User
     */
    public static function create(
        string $email,
        string $username,
        string $password,
        string $role,
        bool $status
    )
    {
        $user = new self;
        $user->setPassword($password);
        $user->generateAuthKey();

        $user->email = $email;
        $user->username = $username;
        $user->role = $role;
        $user->status = $status;

        return $user;
    }

    /**
     * @param string $username
     * @param string $email
     * @param string $role
     * @param bool $status
     */
    public function edit(
        string $username,
        string $email,
        string $role,
        bool $status
    )
    {
        $this->username = $username;
        $this->email = $email;
        $this->role = $role;
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status == true;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return ($this->role == Rbac::ROLE_ADMIN);
    }
}
