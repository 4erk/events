<?php

namespace Events\Event;

use Events\Event;
use Events\Interface\MessageEventInterface;
use Yiisoft\Json\Json;

class MessageEvent extends Event implements MessageEventInterface
{

    const int TYPE_SYSTEM = 0;
    const int TYPE_EVENT = 1;



    public function __construct(
        protected string $name,
        protected mixed $data = null,
        protected mixed $source = null,
        protected int $type = self::TYPE_EVENT,
    ) {
        parent::__construct($name, $data, $source);
    }


    public function getType(): int
    {
        return $this->type;
    }

    public function __toString(): string
    {
        return Json::encode([

            'name'      => $this->name,
            'data'      => $this->data,
            'timestamp' => $this->timestamp,
            'source'    => $this->source,
            'type'      => $this->type,
        ]);
    }

    public function __serialize(): array
    {
        return [
            'name'      => $this->name,
            'data'      => $this->data,
            'timestamp' => $this->timestamp,
            'source'    => $this->source,
            'type'      => $this->type,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->data = $data['data'];
        $this->timestamp = $data['timestamp'];
        $this->source = $data['source'];
        $this->type = $data['type'];
    }
}
