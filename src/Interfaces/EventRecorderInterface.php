<?php

namespace Events\Interfaces;

use App\Interfaces\ClearableInterface;
use App\Interfaces\RecordableInterface;

interface EventRecorderInterface extends RecordableInterface, ClearableInterface
{
    public function getEvents(): array;
    public function getEventsByName(string $eventName): array;
}
