<?php

namespace core\forms\cabinet\Profile;

use core\entities\Customer\Customer;
use yii\base\Model;

class ChangePasswordForm extends Model
{
    /**
     * @var string
     */
    public $oldPassword;
    /**
     * @var string
     */
    public $newPassword;
    /**
     * @var string
     */
    public $repeatNewPassword;
    /**
     * @var Customer
     */
    private $_customer;

    /**
     * ChangePasswordForm constructor.
     * @param Customer $customer
     * @param array $config
     */
    public function __construct(Customer $customer, array $config = [])
    {
        $this->_customer = $customer;
        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'repeatNewPassword'], 'required'],
            ['oldPassword', 'oldPassword'],
            ['newPassword', 'string', 'min' => 6],
            ['repeatNewPassword', 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    /**
     * @param $attribute
     */
    public function oldPassword($attribute)
    {
        if (!$this->_customer->validatePassword($this->oldPassword)) {
            $this->addError($attribute, \Yii::t('profile', 'incorrect_old_password'));
        }
    }

    public function attributeLabels()
    {
        return [
            'oldPassword' => 'Köhnə şifrə',
            'newPassword' => 'Yeni şifrə',
            'repeatNewPassword' => 'Yeni şifrəni tərkaklayın',
        ];
    }
}