<?php

namespace Events\Interface;

interface EventInterface
{
    public function getName(): string;

    public function getData(): mixed;

    public function getTimestamp(): int;
}
