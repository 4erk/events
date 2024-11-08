<?php

namespace Events\Interfaces;

interface EventFactoryInterface
{
    public static function create(string $name, mixed $data, ?TargetInterface $target): EventInterface;
}
