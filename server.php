<?php


use Events\PoolEventDispatcher;
use Swoole\Coroutine;
use Swoole\Timer;
use function Swoole\Coroutine\run;

require_once __DIR__ . '/vendor/autoload.php';


$pool = new PoolEventDispatcher(4);

$pool->once(\Events\Pool::EVENT_START, function() use ($pool) {
    $pool->emit('child_start');
});

$pool->once('child_start', function() use ($pool) {
    $child = new PoolEventDispatcher(4);
    $child->enableCoroutine();
    $child->once(\Events\Pool::EVENT_START, function() use ($child) {
        $child->emit('timer', 'Hello, from child');
    });
    $child->once('timer', function($data) {
        Timer::tick(1000, function () use ($data) {
            echo $data . "\n";

        });
    });
    $child->start();
});

$pool->start();













