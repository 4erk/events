<?php

namespace Events\Interfaces;


interface EventRecorderInterface extends RecordableInterface
{
    /**
     * @return EventInterface[]
     */
    public function getEvents(): array;

    /**
     * @param string $eventName
     * @return EventInterface[]
     */
    public function getEventsByName(string $eventName): array;
}
