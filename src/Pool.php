<?php

namespace Events;

use Events\Interface\EventDispatcherInterface;
use Events\Interface\PoolInterface;
use Swoole\Process\Pool as SwoolePool;
use Swoole\Table;

class Pool implements Interface\EventDispatcherInterface, PoolInterface
{
    const int MASTER_WORKER_ID = 0;
    const string EVENT_START = 'pool.start';
    const string EVENT_STOP = 'pool.stop';
    const string EVENT_MESSAGE = 'pool.message';
    const string EVENT_WORKER_START = 'pool.worker.start';
    const string EVENT_WORKER_STOP = 'pool.worker.stop';
    const string EVENT_WORKER_MESSAGE = 'pool.worker.message';
    private int $id;
    private SwoolePool $pool;


    private EventDispatcherInterface $dispatcher;
    private Table $table;

    public function __construct(private readonly int $size)
    {
        $this->pool = new SwoolePool($size, SWOOLE_IPC_UNIXSOCK);
        $this->pool->set([
            'enable_coroutine'   => true,
            'enable_message_bus' => true,
        ]);
        $this->dispatcher = new EventDispatcher();
        $this->table = new Table($this->size);
        $this->table->column('id', Table::TYPE_INT);
        $this->table->create();
        $this->initEventListeners();
    }

    private function initEventListeners(): void
    {
        $this->pool->on('WorkerStart', function (SwoolePool $pool, int $workerId) {
            $this->id = $workerId;

            if ($this->isMaster()) {
                $this->emit(self::EVENT_START, $workerId);
            }
            else {
                $this->table->set($workerId, ['id' => $workerId, 'status' => 1]);
                $this->emit(self::EVENT_WORKER_START, $workerId);
            }
        });
        $this->pool->on('WorkerStop', function (SwoolePool $pool, int $workerId) {
            $this->table->set($workerId, ['id' => $workerId, 'status' => 0]);
            if ($this->isMaster()) {
                $this->emit(self::EVENT_STOP, $workerId);
            }
            else {
                $this->emit(self::EVENT_WORKER_STOP, $workerId);
            }
        });
        $this->pool->on('Message', function (SwoolePool $pool, mixed $data) {
            $event = unserialize($data, ['allowed_classes' => true]);
            if ($this->isMaster()) {
                $this->emit(self::EVENT_MESSAGE, $event);
            }
            else {
                $this->emit(self::EVENT_WORKER_MESSAGE, $event);
            }
        });

    }

    public function getId(): int
    {
        return $this->id;

    }


    /**
     * @inheritDoc
     */
    public function on(string $name, callable $listener): void
    {
        $this->dispatcher->on($name, $listener);
    }

    /**
     * @inheritDoc
     */
    public function off(string $name, callable $listener): void
    {
        $this->dispatcher->off($name, $listener);
    }

    public function isMaster(): bool
    {
        return $this->id === self::MASTER_WORKER_ID;
    }

    /**
     * @inheritDoc
     */
    public function once(string $name, callable $listener): void
    {
        $this->dispatcher->once($name, $listener);
    }

    /**
     * @inheritDoc
     */
    public function emit(string $name, mixed $data = null): void
    {
        $this->table->set($this->id, ['id' => 0]);
        $this->dispatcher->emit($name, $data);
        $this->table->set($this->id, ['id' => 1]);
    }

    public function isIdle($id): bool
    {
        return $this->table->get($id, 'id') === 1;
    }

    public function start(): void
    {
        $this->pool->start();
    }

    public function stop(): void
    {
        $this->pool->shutdown();
    }

    public function sendMessage(mixed $data, int $id): void
    {
        $this->pool->sendMessage(serialize($data), $id);
    }


    public function getWorkers(): array
    {
        $size = $this->size;
        $workers = [];
        for ($i = 1; $i < $size; $i++) {
            $workers[$i] = (bool) $this->table->get($i, 'id');
        }
        return $workers;
    }

    public function getIdleWorker(): int
    {
        while (true) {
            $workers = $this->getWorkers();
            foreach ($workers as $id => $isIdle) {
                if ($isIdle) {
                    return $id;
                }
            }
            usleep(1000);
        }
    }
}
