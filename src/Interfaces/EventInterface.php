<?php

namespace Events\Interfaces;

interface EventInterface
{
    /**
     * Конструктор события.
     *
     * @param string $name Имя события.
     * @param mixed $data Данные, связанные с событием.
     * @param int $priority Приоритет события (по умолчанию 0).
     */
    public function __construct(string $name, mixed $data, int $priority = 0);

    /**
     * Получает имя события.
     *
     * @return string Имя события.
     */
    public function getName(): string;

    /**
     * Получает временную метку возникновения события.
     *
     * @return float Временная метка события в виде числа с плавающей точкой.
     */
    public function getTimestamp(): float;

    /**
     * Получает данные, связанные с событием.
     *
     * @return mixed Данные, связанные с событием.
     */
    public function getData(): mixed;

    /**
     * Получает приоритет события.
     *
     * @return int Приоритет события в виде целого числа.
     */
    public function getPriority(): int;

    /**
     * Преобразует событие в представление массива.
     *
     * @return array Представление события в виде массива.
     */
    public function toArray(): array;

    /**
     * Сериализует объект события.
     *
     * @return array Сериализованное представление события.
     */
    public function __serialize(): array;

    /**
     * Десериализует объект события.
     *
     * @param array $data Сериализованные данные для восстановления объекта.
     * @return void
     */
    public function __unserialize(array $data): void;

    /**
     * Получает строковое представление события.
     *
     * @return string Строковое представление события.
     */
    public function __toString(): string;
}
