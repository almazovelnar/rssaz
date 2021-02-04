<?php

namespace core\entities\traits;

/**
 * Trait EventTrait
 * @package core\entities
 */
trait EventTrait
{
    private array $_events = [];

    public function recordEvent($event): void
    {
        $this->_events[] = $event;
    }

    public function releaseEvents(): array
    {
        $events = $this->_events;
        $this->_events = [];
        return $events;
    }
}