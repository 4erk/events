<?php


use Events\PoolEventDispatcher;
use Swoole\Coroutine;
use Swoole\Timer;
use function Swoole\Coroutine\run;

require_once __DIR__ . '/vendor/autoload.php';


$pool = new PoolEventDispatcher(3);

$pool->on('test', function ($data) use ($pool) {
    echo "Received data: {$data}\n";
    Timer::tick(1000, function () use ($pool) {
        echo "Tick\n";
        $pool->emit('test2', 'Ticking...');
    });
});

$pool->on('test2', function ($data) use ($pool) {
    echo "Received data: {$data}\n";
});





$pool->on(\Events\Pool::EVENT_START, function () use ($pool) {
    $pool->emit('test', 'Hello, from test');
});


$pool->start();











