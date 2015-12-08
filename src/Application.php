<?php

namespace memclutter\PhpTodo;

/**
 * Class Application
 *
 * @property Request $request
 * @property Response $response
 */
class Application
{
    use ContainerTrait, SingletonTrait;

    public function run()
    {
        $this->request = new Request();
        $path = $this->request->getPath();
        $content = sprintf('application run with path "%s"', $path);
        $this->response = new Response(200, $content);
        $this->response->send();
    }
}