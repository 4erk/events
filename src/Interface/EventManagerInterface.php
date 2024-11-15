<?php

namespace Events\Interface;

interface EventManagerInterface
{
    /**
     * @param string                        $name
     * @param callable(EventInterface):void $listener
     * @return void
     */
    public function on(string $name, callable $listener): void;

    /**
     * @param string                        $name
     * @param callable(EventInterface):void $listener
     * @return void
     */
    public function off(string $name, callable $listener): void;

    /**
     * @param string     $name
     * @param mixed $data
     * @return void
     */
    public function emit(string $name, mixed $data = null, mixed $source = null): void;
}
