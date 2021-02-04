<?php

namespace core\repositories\interfaces;

use core\entities\Session\Session;

/**
 * Interface SessionRepositoryInterface
 * @package core\repositories\interfaces
 */
interface SessionRepositoryInterface
{
    /**
     * @param int $postId
     * @param string $sessionId
     * @return Session|null
     */
    public function postExistsInSession(int $postId, string $sessionId);
    public function getClickCountForIP(string $ip, int $postId);
    public function save(Session $session): Session;
}