<?php

namespace memclutter\PhpTodo;

/**
 * Class Request
 * @package memclutter\PhpTodo
 *
 * @property array $postParams
 */
class Request
{
    use ContainerTrait;

    public function __construct()
    {
        $this->postParams = function() {
            return is_array($_POST) ? $_POST : [];
        };
    }

    public function isPost()
    {
        return !empty($_POST);
    }

    public function getPath()
    {
        if (isset($_SERVER['PATH_INFO'])) {
            $path = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER['SCRIPT_NAME']) && isset($_SERVER['PHP_SELF'])) {
            $path = str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']);
        } elseif (isset($_SERVER['REQUEST_URI']) && isset($_SERVER['SCRIPT_NAME']) && isset($_SERVER['QUERY_STRING'])) {
            $path = str_replace($_SERVER['SCRIPT_NAME'], '', str_replace('?'.$_SERVER['QUERY_STRING'], '', $_SERVER['REQUEST_URI']));
        } else {
            $path = '';
        }

        return trim($path, '/');
    }

    public function getClientIp()
    {
        return Utils::getClientIp();
    }
}