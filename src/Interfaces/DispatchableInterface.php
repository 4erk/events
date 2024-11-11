<?php

namespace Events\Interfaces;

interface DispatchableInterface
{
    public function dispatch(EventInterface $event): void;
}
