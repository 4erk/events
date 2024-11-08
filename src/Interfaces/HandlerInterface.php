<?php

namespace Events\Interfaces;

interface HandlerInterface
{
    public function handle(EventInterface $event): void;
}
