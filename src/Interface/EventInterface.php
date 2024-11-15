<?php

namespace Events\Interface;

interface EventInterface
{
    public function getName(): string;

    public function getData(): mixed;

    public function getTimestamp(): int;

    public function getSource(): mixed;

    public function __toString(): string;

    public function __serialize(): array;

    public function __unserialize(array $data): void;
}
