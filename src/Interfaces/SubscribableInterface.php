<?php

namespace Events\Interfaces;

interface SubscribableInterface
{
    public function subscribe(string $eventName, callable $callback): void;
    public function unsubscribe(string $eventName, callable $callback): void;
}
