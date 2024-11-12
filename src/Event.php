<?php

namespace Events;

use JsonException;
use Yiisoft\Json\Json;

class Event implements Interfaces\EventInterface
{
    private float $timestamp;

    public function __construct(
        private string $name,
        private mixed $data = null,
        private int $priority = 0,
    )
    {
        $this->timestamp = microtime(true);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTimestamp(): float
    {
        return $this->timestamp;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        return Json::encode($this->toArray());
    }

    public function __serialize(): array
    {
        return $this->toArray();
    }

    public function toArray(): array
    {
        return [
            'name'      => $this->name,
            'data'      => $this->data,
            'priority'  => $this->priority,
            'timestamp' => $this->timestamp,
        ];
    }

    public function __unserialize(array $data): void
    {
        [
            'name'      => $this->name,
            'data'      => $this->data,
            'priority'  => $this->priority,
            'timestamp' => $this->timestamp
        ] = $data;
    }
}
