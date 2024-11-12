<?php

namespace Events\Interfaces;

interface SubscribableInterface
{
    public function subscribe(string $eventName, ListenerInterface $listener): void;
    public function unsubscribe(string $eventName, ListenerInterface $listener): void;
}
