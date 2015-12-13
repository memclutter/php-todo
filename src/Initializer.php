<?php

namespace memclutter\PhpTodo;

use PDO;

class Initializer
{
    public function logger(Application $application) {
        $config = $application->config;

        $targetFile = isset($config['logTargetFile']) ? $config['logTargetFile'] : null;
        $level = isset($config['logLevel']) ? $config['logLevel'] : null;
        $dateFormat = isset($config['logDateFormat']) ? $config['logDateFormat'] : null;
        $lineFormat = isset($config['logLineFormat']) ? $config['logLineFormat'] : null;

        return new Logger($targetFile, $level, $dateFormat, $lineFormat);
    }

    public function pdo(Application $application) {
        $config = isset($application->config) && isset($application->config['pdo']) ? $application->config['pdo'] : null;
        if (!$config) {
            throw new Exception('Invalid pdo configuration.');
        }

        $config['username'] = isset($config['username']) ? $config['username'] : '';
        $config['passwd'] = isset($config['passwd']) ? $config['passwd'] : '';
        $config['options'] = isset($config['options']) ? $config['options'] : '';

        return new PDO($config['dsn'], $config['username'], $config['passwd'], $config['options']);
    }

    public function router(Application $application) {
        $config = isset($application->config) ? $application->config : [];
        $routes = isset($config['routes']) ? $config['routes'] : null;
        $defaultRoute = isset($config['defaultRoute']) ? $config['defaultRoute'] : null;
        $controllerNamespace = isset($config['controllerNamespace']) ? $config['controllerNamespace'] : null;

        return new Router($routes, $defaultRoute, $controllerNamespace);
    }

    public function layout(Application $application) {
        $config = isset($application->config) ? $application->config : [];
        $name = isset($config['layout']) ? $config['layout'] : null;

        if (!isset($config['layoutDir'])) {
            throw new Exception('Invalid config, missing \'layoutDir\'.');
        }

        $layoutDir = $config['layoutDir'];
        if (!file_exists($layoutDir)) {
            throw new Exception("Layout dir '{$layoutDir}' not found.");
        }

        $layout = new Layout($layoutDir);
        if (!empty($name)) {
            $layout->name = $name;
        }

        return $layout;
    }
}