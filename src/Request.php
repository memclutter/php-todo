<?php

namespace memclutter\PhpTodo;

class Request
{
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
}