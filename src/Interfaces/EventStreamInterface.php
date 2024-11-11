<?php

namespace Events\Interfaces;

interface EventStreamInterface extends SubscribableInterface, PublishableInterface
{
    public function push(EventInterface $event): void;
    public function addHandler(string $eventName, StreamSubscriberInterface $handler): void;
}
