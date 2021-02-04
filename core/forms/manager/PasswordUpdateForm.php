<?php

namespace core\forms\manager;

use yii\base\Model;

/**
 * PasswordUpdateForm form class
 */
class PasswordUpdateForm extends Model
{
    /**
     * @var string
     */
    public $newPassword;
    /**
     * @var string
     */
    public $repeatPassword;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['newPassword', 'repeatPassword'], 'required'],
            ['newPassword', 'string', 'min' => 6],
            ['repeatPassword', 'compare', 'compareAttribute' => 'newPassword'],
        ];
    }
}