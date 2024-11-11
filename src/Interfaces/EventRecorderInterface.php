<?php

namespace Events\Interfaces;


interface EventRecorderInterface extends RecordableInterface, ClearableInterface
{
    public function getEvents(): array;
    public function getEventsByName(string $eventName): array;
}
