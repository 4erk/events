<?php

namespace Events\Interfaces;

interface TargetInterface
{
    public function on(string $event, callable $handler): void;

    public function off(string $event, ?callable $handler): void;

    public function once(string $event, callable $handler): void;

    public function emit(string $event, mixed $data, ?TargetInterface $target): void;

    public function handle(EventInterface $event,?TargetInterface $current): void;
}
