<?php

namespace memclutter\PhpTodo;

trait ContainerTrait
{
    private $_container = [];

    public function __get($name)
    {
        if (isset($this->_container[$name]) && ($this->_container[$name] instanceof \Closure)) {
            $factory = $this->_container[$name];
            $this->_container[$name] = $factory($this);
        }
        return isset($this->_container[$name]) ? $this->_container[$name] : null;
    }

    public function __set($name, $value)
    {
        $this->_container[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->_container[$name]);
    }

    public function __unset($name)
    {
        if (isset($this->_container[$name])) {
            unset($this->_container[$name]);
        }
    }

    public function get($name, $default = null)
    {
        if (isset($this->_container[$name]) && ($this->_container[$name] instanceof \Closure)) {
            $factory = $this->_container[$name];
            $this->_container[$name] = $factory($this);
        }
        return isset($this->_container[$name]) ? $this->_container[$name] : $default;
    }

    public function set($name, $value)
    {
        $this->_container[$name] = $value;
        return $this;
    }

    public function has($name)
    {
        return isset($this->_container[$name]);
    }

    public function del($name)
    {
        if (isset($this->_container[$name])) {
            unset($this->_container[$name]);
        }
    }

    public function toArray()
    {
        return $this->_container;
    }
}