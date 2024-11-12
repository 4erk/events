<?php

namespace Events\Interfaces;

interface ListenableInterface
{
    /**
     * Регистрирует слушателя события для конкретного события.
     *
     * @param string   $name     Имя события, которое нужно слушать.
     * @param callable $callback Функция, которая будет вызвана при возникновении события.
     *
     * @return void
     */
    public function on(string $name, callable $callback): void;

    /**
     * Удаляет слушателя события для конкретного события.
     *
     * @param string        $name     Имя события, из которого нужно удалить слушателя.
     * @param callable|null $callback Конкретный обратный вызов для удаления. Если null, удаляются все слушатели для этого события.
     *
     * @return void
     */
    public function off(string $name, ?callable $callback): void;
}
