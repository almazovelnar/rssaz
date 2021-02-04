<?php

namespace core\entities;

/**
 * Interface AggregateRoot
 * @package core\entities
 */
interface AggregateRoot
{
    public function releaseEvents();
}