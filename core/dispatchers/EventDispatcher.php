<?php

namespace core\dispatchers;

/**
 * Interface EventDispatcher
 * @package core\dispatchers
 */
interface EventDispatcher
{
    public function dispatch($event);
    public function dispatchAll(array $events);
}