<?php

namespace memclutter\PhpTodo;

use ReflectionMethod;
use ReflectionParameter;

class Controller
{
    use ContainerTrait;

    /**
     * @param $action
     * @param null $params
     * @return mixed|Response
     * @throws Exception
     */
    public function run($action, $params = null)
    {
        $actionMethod = $action . 'Action';
        if (!method_exists($this, $actionMethod)) {
            $controller = get_class($this);
            return new Response(404, "Not found controller action {$controller}::{$action}.");
        }

        $callActionArguments = [];
        $reflectionMethod = new ReflectionMethod($this, $actionMethod);
        $reflectionParameters = $reflectionMethod->getParameters();
        foreach ($reflectionParameters as $reflectionParameter) {
            /* @var ReflectionParameter $reflectionParameter */
            $name = $reflectionParameter->getName();
            if (!isset($params[$name])) {
                if (!$reflectionParameter->isDefaultValueAvailable()) {
                    throw new Exception("Missing required action parameter {$name}.");
                }
                $callActionArguments[$reflectionParameter->getPosition()] = $reflectionParameter->getDefaultValue();
            } else {
                $callActionArguments[$reflectionParameter->getPosition()] = $params[$name];
            }
        }

        $response = $reflectionMethod->invokeArgs($this, $callActionArguments);
        if (!($response instanceof Response)) {
            $response = new Response();
        }
        return $response;
    }
}