<?php

namespace Events\Interfaces;

interface PublishableInterface
{
    public function publish(EventInterface $event): void;
}
