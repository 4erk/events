<?php

namespace Events\Interfaces;

interface EventInterface
{
    public function getName(): string;
    public function getTimestamp(): int;
    public function getData(): mixed;
    public function getPriority(): int;
    public function __toString(): string;
}
