<?php

require __DIR__ . '/vendor/autoload.php';



$pool = new \Swoole\Process\Pool(4);

$pool->on('workerStart', function ($pool, $workerId) {
    echo "Worker {$workerId} started\n";
});

$pool->on('Message', function ($pool, $workerId, $message) {
    echo "Worker {$workerId} received message: {$message}\n";
});

$pool->start();

$pool->sendMessage('Hello, worker 0');
