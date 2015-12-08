<?php

namespace memclutter\PhpTodo;

class Application
{
    public function run()
    {
        $request = new Request();

        $path = $request->getPath();

        $content = sprintf('application run with path "%s"', $path);
        $response = new Response(200, $content);

        $response->send();
    }
}