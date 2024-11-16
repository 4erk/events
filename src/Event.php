<?php

namespace Events;

class Event implements Interface\EventInterface
{

    protected int $timestamp;

    public function __construct(
        protected string $name,
        protected mixed $data = null,
    )
    {
        $this->init();
    }

    protected function init(): void
    {
        $this->timestamp = (int) microtime(true) * 1000;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function getTimestamp(): int
    {
        return $this->timestamp;
    }
}
