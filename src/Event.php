<?php

namespace Events;

use Events\Interfaces\TargetInterface;

readonly class Event implements Interfaces\EventInterface
{

    public function __construct(
        private string $name,
        private mixed $data = null,
        private ?TargetInterface $target = null,
        private bool $once = false
    )
    {

    }

    #[\Override]
    public function getName(): string
    {
        return $this->name;
    }

    #[\Override]
    public function getData(): mixed
    {
        return $this->data;
    }

    #[\Override]
    public function getTarget(): ?TargetInterface
    {
        return $this->target;
    }

    #[\Override]
    public function isOnce(): bool
    {
        return $this->once;
    }

}
