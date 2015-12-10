<?php

namespace memclutter\PhpTodo;

class Router
{
    public $routes = [];
    public $defaultRoute = '';
    public $controllerNamespace = 'controller\\';

    public function __construct($routes = null, $defaultRoute = null, $controllerNamespace = null)
    {
        if (is_array($routes)) {
            $this->routes = $routes;
        }

        if (!empty($defaultRoute)) {
            $this->defaultRoute = $defaultRoute;
        }

        if (!empty($controllerNamespace)) {
            $this->controllerNamespace = $controllerNamespace;
        }
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function run(Request $request)
    {
        $path = $request->getPath();
        $path = $path ? $path : $this->defaultRoute;
        $pathArray = explode('/', trim($path, '/'));

        foreach ($this->routes as $name => $route) {
            $this->validate($name, $route);
            $patternArray = explode('/', trim($route['pattern'], '/'));

            if ($this->match($pathArray, $patternArray, $params)) {
                return $this->dispatch($name, $route, $params);
            }
        }

        return new Response(404, "Not found path {$path}.");
    }

    private function validate($name, $route)
    {
        if (!isset($route['pattern'])) {
            throw new Exception("Invalid route {$name}, missing 'pattern'.");
        }

        if (!isset($route['controller'])) {
            throw new Exception("Invalid route {$name}, missing 'controller'.");
        }
    }

    /**
     * @param array $source
     * @param array $destination
     * @param $params
     * @return boolean
     */
    private function match(array $source, array $destination, &$params)
    {
        $countSource = count($source);
        $countDestination = count($destination);

        if ($countSource != $countDestination) {
            return false;
        }

        for ($i = 0; $i < $countDestination && $i < $countSource; $i++) {
            if ($source[$i] != $destination[$i]) {
                if (substr($destination[$i], 0, 1) == ':') {
                    $params[substr($destination[$i], 1)] = $source[$i];
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param $name
     * @param $route
     * @param $params
     * @return Response
     */
    private function dispatch($name, $route, $params)
    {
        /* @var Controller $controllerClass */
        $controllerClass = implode('\\', [
            trim($this->controllerNamespace, '\\'),
            trim(str_replace(['\\', '/'], '\\', $route['controller']), '\\'),
        ]);

        $action = isset($route['action']) ? $route['action'] : 'index';

        if (!class_exists($controllerClass)) {
            return new Response(404, "Controller {$route['controller']} not found.");
        }

        /* @var \memclutter\PhpTodo\Controller $controller */
        $controller = new $controllerClass();
        return $controller->run($action, $params);
    }
}