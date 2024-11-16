<?php

namespace Events\Interface;

interface QueueInterface
{
    public function push(mixed $data): void;

    public function pop(): mixed;
}
