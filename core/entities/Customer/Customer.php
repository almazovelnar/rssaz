<?php

namespace core\entities\Customer;

use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use core\entities\Customer\Website\Website;
use core\entities\traits\{Identity, PasswordReset, SignUpConfirm};

/**
 * Class Customer
 * @package core\entities\Customer
 * @property int $id
 * @property string $thumb
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $sites_list
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email_confirm_token
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Website[] $websites
 */
class Customer extends ActiveRecord implements IdentityInterface
{
    use Identity, SignUpConfirm, PasswordReset;

    const STATUS_ACTIVE = 'active';
    const STATUS_WAIT = 'wait';
    const STATUS_BLOCKED = 'blocked';

    public static string $activeStatus = self::STATUS_ACTIVE;

    public static function tableName()
    {
        return '{{%customers}}';
    }

    public static function requestSignUp($name, $surname, $email, $password, $siteList = []): self
    {
        $customer = new self();
        $customer->name = $name;
        $customer->surname = $surname;
        $customer->email = $email;
        $customer->sites_list = !empty($siteList) ? json_encode($siteList) : null;
        $customer->setPassword($password);
        $customer->generateAuthKey();
        $customer->generateEmailConfirmToken();
        $customer->status = self::STATUS_WAIT;
        return $customer;
    }

    public static function create($name, $surname, $email, $password, $status)
    {
        $customer = new self();
        $customer->name = $name;
        $customer->surname = $surname;
        $customer->email = $email;
        $customer->setPassword($password);
        $customer->generateAuthKey();
        $customer->status = $status;
        return $customer;
    }

    public function addThumb($thumb)
    {
        $this->thumb = $thumb;
    }

    public function removeThumb()
    {
        $this->thumb = null;
    }

    public function getAvatar()
    {
        return ($this->thumb) ? $this->thumb : 'user.png';
    }

    public function edit($name, $surname, $email, $status): void
    {
        $this->name = $name;
        $this->surname = $surname;
        $this->email = $email;
        $this->status = $status;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSitesList(): ?array
    {
        return json_decode($this->sites_list);
    }

    public function editProfile($name, $surname): void
    {
        $this->name = $name;
        $this->surname = $surname;
    }

    public function getFullName()
    {
        return $this->surname . ' ' . $this->name;
    }

    public function isActive()
    {
        return $this->status == self::STATUS_ACTIVE;
    }

    public function getWebsites()
    {
        return $this->hasMany(Website::class, ['customer_id' => 'id']);
    }
}