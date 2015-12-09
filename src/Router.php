<?php

namespace memclutter\PhpTodo;

class Router
{
    public $rules = [];

    public $defaultRoute = '';

    public function __construct()
    {
        $app = Application::getInstance();
        $config = $app->config;
        if (isset($config['router']['rules'])) {
            $this->rules = $config['router']['rules'];
        }
        if (isset($config['router']['defaultRoute'])) {
            $this->defaultRoute = $config['router']['defaultRoute'];
        }
    }

    /**
     * @param Request $request
     * @return Controller
     */
    public function dispatch(Request $request)
    {
        $path = $request->getPath();
        $match = $this->match($path);
        if (is_array($match)) {
            $template = new Template();
            $container = isset($match['container']) ? $match['container'] : [];
            $params = isset($match['params']) ? $match['params'] : [];
            foreach ($params as $name => $value) {
                $template->set($name, $value);
            }
            foreach ($container as $name => $value) {
                if ($value instanceof \Closure) {
                    $value = call_user_func($value, $template);
                }
                $template->set($name, $value);
            }
            $templateFile = isset($match['templateFile']) ? $match['templateFile'] : null;
            $content = $template->render($templateFile);
            return new Response(200, $content);
        } else {
            return new Response(404, 'Not found');
        }
    }

    public function match($path)
    {
        if (empty($path)) {
            $path = $this->defaultRoute;
        }

        $search = null;
        $params = [];
        foreach ($this->rules as $rule) {
            $patternParts = explode('/', trim($rule['pattern']));
            $pathParts = explode('/', trim($path));
            $search = '';
            $params = [];

            for ($i = 0; $i < count($pathParts); $i++) {
                if (!isset($patternParts[$i])) {
                    $search = null;
                    break;
                } elseif ($pathParts[$i] == $patternParts[$i]) {
                    $search = implode('/', [$search, $pathParts[$i]]);
                } elseif (substr($patternParts[$i], 0, 1) == ':') {
                    $params[substr($patternParts[$i], 1)] = $pathParts[$i];
                    $search = implode('/', [$search, $pathParts[$i]]);
                } else {
                    $search = null;
                    break;
                }
            }

            if ($search !== null) {
                $rule['params'] = $params;
                return $rule;
            }
        }

        return null;
    }

    public function reverse($ruleName, $params = [])
    {
        foreach ($this->rules as $rule) {
            if ($rule['name'] == $ruleName) {
               return '/'.str_replace(array_keys($params), array_values($params), $rule['pattern']);
            }
        }

        return '';
    }
}