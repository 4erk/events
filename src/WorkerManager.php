<?php

namespace Events;

use Events\Event\MessageEvent;
use Events\Interface\EventInterface;
use Events\Interface\MessageEventInterface;
use Swoole\Process\Pool;

class WorkerManager extends EventManager
{
    const string EVENT_MASTER_START = 'manager.master.start';
    const string EVENT_MASTER_STOP = 'manager.master.stop';
    const string EVENT_MASTER_MESSAGE = 'manager.master.message';
    const string EVENT_WORKER_START = 'manager.worker.start';
    const string EVENT_WORKER_STOP = 'manager.worker.stop';
    const string EVENT_WORKER_MESSAGE = 'manager.worker.message';

    const string EVENT_WORKER_MESSAGE_START = 'manager.worker.message.start';
    const string EVENT_WORKER_MESSAGE_STOP = 'manager.worker.message.stop';

    const int MASTER_WORKER_ID = 0;

    private Pool $pool;
    private int $worker;
    /**
     * @var bool[]
     */
    private array $workers = [];


    public function __construct(private readonly int $count)
    {
        $this->pool = new Pool($this->count, SWOOLE_IPC_UNIXSOCK);
        $this->init();
    }

    private function init(): void
    {
        $this->pool->set([
            'enable_coroutine'   => true,
            'enable_message_bus' => true,
        ]);
        $this->pool->on('WorkerStart', [$this, 'onStart']);
        $this->pool->on('WorkerStop', [$this, 'onStop']);
        $this->pool->on('Message', [$this, 'onWorkerMessage']);
        $this->on(self::EVENT_WORKER_START, [$this, 'onWorkerStart']);
        $this->on(self::EVENT_WORKER_STOP, [$this, 'onWorkerStop']);
        $this->on(self::EVENT_WORKER_MESSAGE, [$this, 'onMasterMessage']);
        $this->on(self::EVENT_MASTER_MESSAGE, [$this, 'onMessage']);
    }

    public function onStart(Pool $pool, int $workerId): void
    {
        $this->worker = $workerId;
        if ($this->worker === self::MASTER_WORKER_ID) {
            $this->emit(self::EVENT_MASTER_START, $workerId);
        }
        else {
            $this->emit(self::EVENT_WORKER_START, $workerId);
        }
    }

    public function onStop(Pool $pool, int $workerId): void
    {
        $this->emit(self::EVENT_WORKER_STOP, $workerId);
        if ($this->worker === self::MASTER_WORKER_ID) {
            $this->emit(self::EVENT_MASTER_STOP, $workerId);
        }
        else {
            $this->emit(self::EVENT_WORKER_STOP, $workerId);
        }
    }

    public function onWorkerMessage(Pool $pool, string $message): void
    {
        $event = unserialize($message, ['allowed_classes' => [MessageEvent::class]]);
        if ($this->worker === self::MASTER_WORKER_ID) {
            $this->emit(self::EVENT_WORKER_MESSAGE, $event);
        }
        else {
            $this->emit(self::EVENT_MASTER_MESSAGE, $event);
        }
    }

    public function onWorkerStart(EventInterface $event): void
    {
        $workerId = $event->getData();
        $message = new MessageEvent(
            self::EVENT_WORKER_MESSAGE_START, $workerId, $this->worker, MessageEvent::TYPE_SYSTEM
        );
        $this->sendMessage($message, self::MASTER_WORKER_ID);
    }

    public function onWorkerStop(EventInterface $event): void
    {
        $workerId = $event->getData();
        $message =
            new MessageEvent(self::EVENT_WORKER_MESSAGE_STOP, $workerId, $this->worker, MessageEvent::TYPE_SYSTEM);
        $this->sendMessage($message, self::MASTER_WORKER_ID);
    }

    public function onMasterMessage(MessageEventInterface $event): void
    {
        match ($event->getType()) {
            MessageEvent::TYPE_SYSTEM => $this->onSystemMessage($event),
            MessageEvent::TYPE_EVENT => $this->onEventMessage($event),
        };
    }

    public function onMessage(MessageEventInterface $event): void
    {
        match ($event->getType()) {
            MessageEvent::TYPE_SYSTEM => $this->onSystemMessage($event),
            MessageEvent::TYPE_EVENT => $this->onEventMessage($event),
        };
    }


    public function start(): void
    {
        $this->pool->start();
    }

    public function getPool(): Pool
    {
        return $this->pool;
    }

    public function sendMessage(MessageEventInterface $message, int $workerId): void
    {
        $this->pool->sendMessage(serialize($message), $workerId);
    }

    private function onSystemMessage(MessageEventInterface $event): void
    {
        if ($this->worker === self::MASTER_WORKER_ID) {
            switch ($event->getName()) {
                case self::EVENT_WORKER_MESSAGE_START:
                    $this->workers[$event->getData()] = true;
                    break;
                case self::EVENT_WORKER_MESSAGE_STOP:
                    unset($this->workers[$event->getData()]);
                    break;
                default:
                    $this->emit($event->getName(), $event->getData(), $event->getSource());
            }
        }
        else {
            $this->emit($event->getName(), $event->getData(), $event->getSource());
        }
    }

    public function send(MessageEventInterface $message): void
    {
        if ($this->worker === self::MASTER_WORKER_ID) {
            $workerId = array_rand($this->workers);
        }
        else {
            $workerId = self::MASTER_WORKER_ID;
        }
        $this->sendMessage($message, $workerId);
    }

    public function emit(string $name, mixed $data = null, mixed $source = null): void
    {
        if ($this->isSystemEvent($name)) {
            parent::emit($name, $data, $source);
        }
        else {
            $message = new MessageEvent($name, $data, $source ?? $this->worker, MessageEvent::TYPE_EVENT);
            $this->send($message);
        }
    }

    public function isSystemEvent(string $name): bool
    {
        return in_array($name, [
            self::EVENT_MASTER_START,
            self::EVENT_MASTER_STOP,
            self::EVENT_WORKER_START,
            self::EVENT_WORKER_STOP,
            self::EVENT_WORKER_MESSAGE,
            self::EVENT_WORKER_MESSAGE_START,
            self::EVENT_WORKER_MESSAGE_START,
            self::EVENT_WORKER_MESSAGE_STOP,
        ]);
    }

    private function onEventMessage(MessageEventInterface $event): void
    {
        if ($this->worker === self::MASTER_WORKER_ID) {
            $this->send($event);
        }
        else {
            $this->emit($event->getName(), $event->getData(), $event->getSource());
        }
    }
}
