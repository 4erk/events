<?php

namespace Events\Interface;

interface PoolInterface
{
    public function getId(): int;

    public function isMaster(): bool;

    public function isIdle($id): bool;

    public function start(): void;

    public function stop(): void;

    public function sendMessage(mixed $data, int $id): void;

    /**
     * @return bool[]
     */
    public function getWorkers(): array;

    public function getIdleWorker(): int;
}
