<?php

namespace Events;

use Events\Interface\EventInterface;
use Events\Interface\EventDispatcherInterface;

class EventDispatcher implements EventDispatcherInterface
{
    private array $listeners = [];

    /**
     * @param string   $name
     * @param callable $listener
     * @param bool     $once
     * @inheritdoc
     */
    public function on(string $name, callable $listener, bool $once = false): void
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
    public function emit(string $name, mixed $data = null): void
    {
        if (!isset($this->listeners[$name])) {
            return;
        }
        foreach ($this->listeners[$name] as $listener) {
            $listener($data);
        }
    }

    public function once(string $name, callable $listener): void
    {
        $this->on($name, function ($data) use ($name, $listener) {
            $this->off($name, $listener);
            $listener($data);
        });
    }
}
