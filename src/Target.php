<?php

namespace Events;

use Events\Interfaces\EventInterface;
use Events\Interfaces\TargetInterface;

class Target implements TargetInterface
{
    /**
     * @var array<string, callable[]> $handlers = [];
     */
    private array $handlers = [];

    public function on(string $event, callable $handler): void
    {
        if (!isset($this->handlers[$event])) {
            $this->handlers[$event] = [];
        }
        $this->handlers[$event][] = $handler;
    }

    public function off(string $event, ?callable $handler): void
    {
        if (isset($this->handlers[$event])) {
            $key = array_search($handler, $this->handlers[$event], true);
            if ($key !== false) {
                unset($this->handlers[$event][$key]);
            }
        }
    }

    public function once(string $event, callable $handler): void
    {
        $this->on($event, function (EventInterface $event, mixed $data) use ($handler) {
            $handler($event, $data);
            $this->off($event->getName(), $handler);
        });
    }

    public function emit(string $event, mixed $data = null, ?TargetInterface $target = null): void
    {
        $eventObject = EventFactory::create($event, $data, $target);
        $this->handle($eventObject, $this);
    }

    public function handle(EventInterface $event, ?TargetInterface $current): void
    {
        if (isset($this->handlers[$event->getName()])) {
            foreach ($this->handlers[$event->getName()] as $handler) {
                $handler($event, $current);
            }
        }
    }
}
