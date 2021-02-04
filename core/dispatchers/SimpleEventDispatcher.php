<?php

namespace core\dispatchers;

use yii\di\Container;

class SimpleEventDispatcher implements EventDispatcher
{
    private $listeners;
    private $container;

    public function __construct(Container $container, array $listeners)
    {
        $this->container = $container;
        $this->listeners = $listeners;
    }

    public function dispatchAll(array $events)
    {
        foreach ($events as $event) {
            $this->dispatch($event);
        }
    }

    public function dispatch($event)
    {
        $eventName = get_class($event);
        if (array_key_exists($eventName, $this->listeners)) {
            foreach ($this->listeners[$eventName] as $listenerClass) {
                $listener = $this->resolveListener($listenerClass);
                $listener($event);
            }
        }
    }

    private function resolveListener($listenerClass)
    {
        return [$this->container->get($listenerClass), 'handle'];
    }
}