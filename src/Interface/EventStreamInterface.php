<?php

namespace Events\Interface;

interface EventStreamInterface
{
    public function subscribe(string $name, callable $callback): void;

    public function unsubscribe(string $name, callable $callback): void;

    public function publish(string $name, mixed $payload): void;

    public function notify(string $name, mixed $payload): void;
}
