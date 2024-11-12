<?php

namespace Events\Interfaces;

interface ListenerInterface
{
    public function handle(EventInterface $event): void;
}
