<?php

namespace memclutter\PhpTodo;

trait SingletonTrait
{
    protected static $_instance;

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
}