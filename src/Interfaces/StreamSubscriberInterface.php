<?php

namespace Events\Interfaces;

interface StreamSubscriberInterface
{
    public function handle(EventInterface $event): void;
    public function getSubscribedEvents(): array;
}
