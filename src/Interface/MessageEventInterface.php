<?php

namespace Events\Interface;

/**
 * @method getSource(): WorkerInterface
 */
interface MessageEventInterface extends EventInterface
{
    public function getType(): int;
}
