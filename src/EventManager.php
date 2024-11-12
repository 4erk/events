<?php

namespace Events;

use Events\Interfaces\EventLoopInterface;
use Events\Interfaces\EventManagerInterface;

class EventManager implements EventManagerInterface
{
    private EventLoopInterface $eventLoop;
    private array $listeners = [];

    public function __construct(EventLoopInterface $eventLoop)
    {
        $this->eventLoop = $eventLoop;
    }

    public function emit(string $event, $data): void
    {
        if (!isset($this->listeners[$event])) {
            return;
        }

        foreach ($this->listeners[$event] as $callback) {
            $this->eventLoop->addTask(function () use ($callback, $data) {
                $callback($data);
            });
        }
    }

    public function on(string $name, callable $callback): void
    {
        if (!isset($this->listeners[$name])) {
            $this->listeners[$name] = [];
        }
        $this->listeners[$name][] = $callback;
    }

    public function off(string $name, ?callable $callback = null): void
    {
        if (!isset($this->listeners[$name])) {
            return;
        }

        if ($callback === null) {
            unset($this->listeners[$name]);
        } else {
            $this->listeners[$name] = array_filter(
                $this->listeners[$name],
                static function ($existingCallback) use ($callback) {
                    return $existingCallback !== $callback;
                }
            );
        }
    }

    public function getEventLoop(): EventLoopInterface
    {
        return $this->eventLoop;
    }
}
