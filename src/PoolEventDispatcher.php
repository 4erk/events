<?php

namespace Events;

use Events\Event\MessageEvent;

class PoolEventDispatcher implements Interface\EventDispatcherInterface
{

    private Pool $pool;

    public function __construct(private readonly int $size)
    {
        $this->pool = new Pool($this->size);
        $this->initEventListeners();
    }

    private function initEventListeners(): void
    {
        $this->pool->on(Pool::EVENT_MESSAGE, function (MessageEvent $event) {
            $workerId = $this->pool->getIdleWorker();
            $this->pool->sendMessage($event, $workerId);
        });
        $this->pool->on(Pool::EVENT_WORKER_MESSAGE, function (MessageEvent $event) {

            $this->pool->emit($event->getName(), $event->getData());
        });
    }

    public function on(string $name, callable $listener): void
    {
        $this->pool->on($name, $listener);
    }

    public function off(string $name, callable $listener): void
    {
        $this->pool->off($name, $listener);
    }

    public function once(string $name, callable $listener): void
    {
        $this->pool->once($name, $listener);
    }

    public function emit(string $name, mixed $data = null): void
    {
        $event = new MessageEvent($name, $data, $this->pool->getId());
        $workerId = $this->pool->isMaster() ? $this->pool->getIdleWorker() : Pool::MASTER_WORKER_ID;
        $this->pool->sendMessage($event, $workerId);
    }

    public function start(): void
    {
        $this->pool->start();
    }

    public function stop(): void
    {
        $this->pool->stop();
    }


}
