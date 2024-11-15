<?php

namespace Events;

use JsonException;
use Yiisoft\Json\Json;

class Event implements Interface\EventInterface
{

    protected int $timestamp;

    public function __construct(
        protected string $name,
        protected mixed $data = null,
        protected mixed $source = null
    )
    {
        $this->init();
    }

    private function init(): void
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

    public function getSource(): mixed
    {
        return $this->source;
    }

    /**
     * @throws JsonException
     */
    public function __toString(): string
    {
        return Json::encode([
            'name'      => $this->name,
            'data'      => $this->data,
            'timestamp' => $this->timestamp,
            'source'    => $this->source,
        ]);
    }

    public function __serialize(): array
    {
        return [
            'name'      => $this->name,
            'data'      => $this->data,
            'timestamp' => $this->timestamp,
            'source'    => $this->source,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->data = $data['data'];
        $this->timestamp = $data['timestamp'];
        $this->source = $data['source'];
    }
}
