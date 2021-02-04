<?php

namespace core\useCases\events;

use core\entities\Customer\Customer;

class SignUpRequested
{
    public $user;

    public function __construct(Customer $user)
    {
        $this->user = $user;
    }
}