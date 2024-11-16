<?php

namespace Events\Event;


use Events\Event;

class MessageEvent extends Event
{


    public function __construct(string $name, mixed $data, protected readonly string $id)
    {
        parent::__construct($name, $data);
    }

    public function getId(): string
    {
        return $this->id;
    }
}
