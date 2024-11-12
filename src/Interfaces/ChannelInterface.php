<?php

namespace Events\Interfaces;

interface ChannelInterface
{
    public function push(EventInterface $event, int $timeout): void;

    public function pop():?EventInterface;

    public function count(): int;

    public function close(): void;

}
