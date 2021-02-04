<?php

namespace core\useCases\events;

use core\entities\Customer\Customer;

class PasswordResetRequested
{
    public $user;

    public function __construct(Customer $user)
    {
        $this->user = $user;
    }
}