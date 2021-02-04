<?php

namespace core\repositories\interfaces;

/**
 * Interface UserRepositoryInterface
 * @package core\repositories\interfaces
 */
interface UserRepositoryInterface
{
    public function getByUsernameOrEmail($username);
}