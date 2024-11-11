<?php

namespace Events\Interfaces;

interface EventManagerInterface extends SubscribableInterface, DispatchableInterface
{
    public function emit(EventInterface $event): void;
}
