<?php

namespace Events\Interfaces;

interface EmittableInterface
{
    public function emit(string $event, $data): void;
}
