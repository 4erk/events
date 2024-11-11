<?php

namespace Events\Interfaces;

interface RecordableInterface
{
    public function record(EventInterface $event): void;
}
