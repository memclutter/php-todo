<?php

namespace memclutter\PhpTodo;

class Router
{
    public $rules = [];

    public $defaultRouter = '';

    /**
     * @param Request $request
     * @return Controller
     */
    public function dispatch(Request $request)
    {

    }
}