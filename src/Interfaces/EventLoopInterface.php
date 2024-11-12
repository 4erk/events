<?php

namespace Events\Interfaces;

interface EventLoopInterface
{
    /**
     * Запускает цикл событий
     */
    public function run(): void;
    /**
     * Добавляет задачу в очередь на выполнение
     * @param callable $task    Задача для выполнения
     */
    public function addTask(callable $task): void;
}
