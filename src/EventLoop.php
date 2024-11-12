<?php

namespace Events;

use Events\Interfaces\EventLoopInterface;
use Swoole\Process\Pool;
use Swoole\Atomic;

class EventLoop implements EventLoopInterface
{

    private Pool $pool;
    private Atomic $atomic;
    private $tasks = [];


    public function __construct(int $workerCount) {
        $this->pool = new Pool($workerCount);
        $this->atomic = new Atomic();


    }

    public function run(): void
    {
    }


    public function addTask(callable $task): void
    {
    }
}
