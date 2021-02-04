<?php

namespace core\forms\manager\User;

use yii\base\Model;
use core\entities\User;

/**
 * Class UpdateForm
 *
 * @package core\forms\manager\User
 */
class UpdateForm extends Model
{
    /**
     * @var string
     */
    public $username;
    /**
     * @var string
     */
    public $email;
    /**
     * @var string
     */
    public $role;
    /**
     * @var int
     */
    public $status;
    /**
     * @var User
     */
    private $_user;

    /**
     * UpdateForm constructor.
     * @param User $user
     * @param array $config
     */
    public function __construct(User $user, array $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->role = $user->role;
        $this->status = $user->status;
        $this->_user = $user;

        parent::__construct($config);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['username', 'email', 'role', 'status'], 'required'],
            [['email'], 'email'],
            [['email'], 'string', 'max' => 30],
            [['email'], 'trim'],
            ['email', 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
            ['status', 'integer'],
        ];
    }
}