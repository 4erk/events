<?php

namespace Events\Interfaces;

interface EventInterface
{

    public function getName(): string;

    public function getData(): mixed;

    public function getTarget(): ?TargetInterface;

    public function isOnce(): bool;
}
