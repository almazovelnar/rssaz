<?php

namespace core\useCases\auth;

use core\dispatchers\EventDispatcher;
use core\entities\Customer\Customer;
use core\exceptions\NotFoundException;
use core\repositories\CustomerRepository;
use core\useCases\events\PasswordResetRequested;
use core\forms\auth\cabinet\PasswordResetRequestForm;
use core\forms\auth\cabinet\PasswordResetForm;
use Yii;

class PasswordResetService
{
    private $customers;
    private $dispatcher;

    public function __construct(CustomerRepository $customers, EventDispatcher $dispatcher)
    {
        $this->customers = $customers;
        $this->dispatcher = $dispatcher;
    }

    public function request(PasswordResetRequestForm $form)
    {
        /* @var $customer Customer */
        try {
            $customer = $this->customers->getByEmail($form->email);
        } catch (NotFoundException $e) {
            throw new \DomainException('user_not_found');
        }

        if (!empty($customer->password_reset_token) && self::passwordResetTokenIsValid($customer->password_reset_token)) {
            throw new \DomainException('password_reset_token_already_has_been_sent');
        }

        $customer->generatePasswordResetToken();
        $this->customers->save($customer);

        $this->dispatcher->dispatch(new PasswordResetRequested($customer));
    }

    public function validateToken($token)
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('password_reset_token_required');
        }

        if (!self::passwordResetTokenIsValid($token)) {
            throw new \DomainException('password_reset_token_expired');
        }

        if (!$this->customers->existsByPasswordResetToken($token)) {
            throw new \DomainException('password_reset_token_wrong');
        }
    }

    public function reset($token, PasswordResetForm $form)
    {
        /** @var customer $customer */
        try {
            $customer = $this->customers->getByPasswordResetToken($token);
        } catch (NotFoundException $e) {
            throw new \DomainException('password_reset_token_wrong');
        }

        $customer->resetPassword($form->password);
        $this->customers->save($customer);
    }

    private static function passwordResetTokenIsValid($token)
    {
        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }
}