<?php

namespace Events;

use Events\Interface\QueueInterface;

class Queue implements QueueInterface
{
    private array $items = [];

    public function push(mixed $data): void
    {
        $this->items[] = $data;
    }

    public function pop(): mixed
    {
        if (empty($this->items)) {
            return null;
        }
        return array_shift($this->items);
    }
}
