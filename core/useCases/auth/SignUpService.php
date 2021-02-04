<?php

namespace core\useCases\auth;

use core\dispatchers\EventDispatcher;
use core\exceptions\NotFoundException;
use core\repositories\CustomerRepository;
use core\entities\Customer\Customer;
use core\forms\auth\cabinet\SignUpForm;
use core\useCases\events\SignUpRequested;

class SignUpService
{
    private $customers;
    private $dispatcher;

    public function __construct(CustomerRepository $customers, EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->customers = $customers;
    }

    public function requestSignUp(SignUpForm $form)
    {
        $customer = Customer::requestSignup($form->name, $form->surname, $form->email, $form->password, $form->sitesList);

        $this->customers->save($customer);

        $this->dispatcher->dispatch(new SignUpRequested($customer));
    }

    public function confirm($token)
    {
        if(empty($token)) {
            throw new \DomainException('email_confirm_token_required');
        }

        /** @var Customer $customer */
        try {
            $customer = $this->customers->getByEmailConfirmToken($token);
        } catch (NotFoundException $e) {
            throw new \DomainException('email_confirm_token_wrong');
        }

        $customer->confirmSignup();
        $this->customers->save($customer);
        return $customer;
    }
}