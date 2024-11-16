<?php

namespace Events\Interface;

interface EventDispatcherInterface
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
     * @param string                        $name
     * @param callable(EventInterface):void $listener
     * @return void
     */
    public function once(string $name, callable $listener): void;

    /**
     * @param string $name
     * @param mixed  $data
     * @return void
     */
    public function emit(string $name, mixed $data = null): void;
}
