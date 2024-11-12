<?php

namespace Events;

use Events\Interfaces\ChannelInterface;
use Events\Interfaces\EventInterface;
use Events\Interfaces\EventLoopInterface;
use Events\Interfaces\EventStreamInterface;
use Events\Interfaces\ListenerInterface;

class EventStream implements EventStreamInterface
{
    private EventLoopInterface $eventLoop;
    private ChannelInterface $channel;
    private array $subscribers = [];
    private bool $isRunning = false;

    public function __construct(EventLoopInterface $eventLoop, ChannelInterface $channel)
    {
        $this->eventLoop = $eventLoop;
        $this->channel = $channel;
    }

    public function start(): void
    {
        if (!$this->isRunning) {
            $this->isRunning = true;
            $this->startEventProcessing();
        }
    }

    public function stop(): void
    {
        $this->isRunning = false;
    }

    public function publish(EventInterface $event): void
    {
        $this->eventLoop->addTask(function () use ($event) {
            $this->channel->push($event, -1);
        });
    }

    public function subscribe(string $eventName, ListenerInterface $listener): void
    {
        if (!isset($this->subscribers[$eventName])) {
            $this->subscribers[$eventName] = [];
        }
        $this->subscribers[$eventName][] = $listener;
    }

    public function unsubscribe(string $eventName, ListenerInterface $listener): void
    {
        if (!isset($this->subscribers[$eventName])) {
            return;
        }
        $this->subscribers[$eventName] = array_filter(
            $this->subscribers[$eventName],
            static function ($existingListener) use ($listener) {
                return $existingListener !== $listener;
            }
        );
    }

    private function startEventProcessing(): void
    {
        $this->eventLoop->addTask(function () {
            while (true) {
                $event = $this->channel->pop();
                if ($event !== null) {
                    $this->processEvent($event);
                }
            }
        });
    }

    private function processEvent(EventInterface $event): void
    {
        $eventName = $event->getName();
        if (isset($this->subscribers[$eventName])) {
            foreach ($this->subscribers[$eventName] as $listener) {
                $this->eventLoop->addTask(function () use ($listener, $event) {
                    $listener->handle($event);
                });
            }
        }
    }

    public function getChannel(): ChannelInterface
    {
        return $this->channel;
    }
}
