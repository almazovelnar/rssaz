<?php

namespace core\forms\auth;

use core\entities\User;
use yii\base\Model;

class PasswordChangeForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $repeatNewPassword;

    private $_user;

    public function __construct(User $user, array $config = [])
    {
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'repeatNewPassword'], 'required'],
            ['oldPassword', 'oldPassword'],
            ['newPassword', 'string', 'min' => 6],
            ['repeatNewPassword', 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }

    public function oldPassword($attribute)
    {
        if (!$this->_user->validatePassword($this->oldPassword)) {
            $this->addError($attribute,'Old password is incorrect');
        }
    }
}