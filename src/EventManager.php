<?php

namespace Events;

use Events\Interface\EventInterface;
use Events\Interface\EventManagerInterface;

class EventManager implements EventManagerInterface
{
    private array $listeners = [];

    /**
     * @inheritdoc
     */
    public function on(string $name, callable $listener): void
    {
        if (!isset($this->listeners[$name])) {
            $this->listeners[$name] = [];
        }
        $this->listeners[$name][] = $listener;
    }

    /**
     * @inheritdoc
     */
    public function off(string $name, callable $listener): void
    {
        if (!isset($this->listeners[$name])) {
            return;
        }

        $index = array_search($listener, $this->listeners[$name], true);
        if ($index !== false) {
            unset($this->listeners[$name][$index]);
        }
    }

    /**
     * @inheritdoc
     */
    public function emit(string $name, mixed $data = null, mixed $source = null): void
    {
        if (!isset($this->listeners[$name])) {
            return;
        }
        $event = $data instanceof EventInterface ? $data : new Event($name, $data, $source ?? $this);
        foreach ($this->listeners[$name] as $listener) {
            $listener($event);
        }
    }
}
