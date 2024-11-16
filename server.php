<?php


use Events\PoolEventDispatcher;

require_once __DIR__ . '/vendor/autoload.php';


$pool = new PoolEventDispatcher(3);

$pool->on('test', function ($data) use ($pool) {
    echo "Received data: {$data}\n";
    $pool->emit('test2', 'Hello, from master');
});

$pool->on(\Events\Pool::EVENT_WORKER_START, function () use ($pool) {
    $pool->emit('test', 'Hello, from worker');
});


$pool->start();











