<?php

require_once __DIR__ . '/vendor/autoload.php';

$pool = new \Swoole\Process\Pool(2);

$pool->on('workerStart', function ($pool, $workerId) {
    echo "Worker {$workerId} started\n";
    $server = new Swoole\Http\Server('0.0.0.0', 9501 + $workerId);

    $server->on('request', function ($request, $response) use ($workerId)  {
        $response->end('Hello, World! Worker ID: '.$workerId);
    });
    $server->start();
});


$pool->start();



