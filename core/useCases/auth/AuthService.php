<?php

namespace core\useCases\auth;

use Yii;
use DomainException;
use core\entities\User;
use core\forms\auth\LoginForm;
use core\exceptions\NotFoundException;
use core\repositories\interfaces\UserRepositoryInterface;

/**
 * Class AuthService
 * @package core\useCases\auth
 */
class AuthService
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function auth(LoginForm $form)
    {
        /** @var User $user */
        try {
            $user = $this->userRepository->getByUsernameOrEmail($form->username);
            Yii::$app->user->login($user, $form->rememberMe ? Yii::$app->params['user.sessionDurationExpiry'] : 0);
        } catch (NotFoundException $e) {
            throw new DomainException('user_not_found');
        }

        if(!$user || !$user->isActive() || !$user->validatePassword($form->password)) {
            throw new DomainException('user_not_validated');
        }

        return $user;
    }
}