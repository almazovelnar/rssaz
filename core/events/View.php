<?php

namespace core\events;

use core\entities\Session\Session;

/**
 * Class View
 * @package core\events
 */
class View
{
    private Session $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function getSession(): Session
    {
        return $this->session;
    }
}
