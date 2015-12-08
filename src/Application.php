<?php

namespace memclutter\PhpTodo;

class Application
{
    public function run()
    {
        $request = new Request();

        $path = $request->getPath();

        echo 'application run with path "' . $path . '"';
    }
}