<?php

namespace Events;

use Events\Interfaces\EventFactoryInterface;
use Events\Interfaces\EventInterface;
use Events\Interfaces\TargetInterface;

class EventFactory implements Interfaces\EventFactoryInterface
{

    #[\Override]
    public static function create(string $name, mixed $data, ?TargetInterface $target): EventInterface
    {
        return new Event($name, $data, $target);
    }
}
