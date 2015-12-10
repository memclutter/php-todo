<?php

namespace memclutter\PhpTodo;

use PDO;

/**
 * Class Application
 *
 * @property Request $request
 * @property Response $response
 * @property array $config
 * @property PDO $pdo
 * @property Router $router
 */
class Application
{
    use ContainerTrait, SingletonTrait;

    public function init($environment = '')
    {
        if (!defined('APP_ROOT')) {
            define('APP_ROOT', dirname(__DIR__));
        }

        if (!file_exists(APP_ROOT . DIRECTORY_SEPARATOR . 'config.php')) {
            throw new Exception('Not found config.php.');
        }

        $this->config = require(APP_ROOT . DIRECTORY_SEPARATOR . 'config.php');

        if (!empty($environment)) {
            if (!file_exists(APP_ROOT . DIRECTORY_SEPARATOR . 'config.' . $environment . '.php')) {
                throw new Exception("Not found config file for environment {$environment}.");
            }

            /** @noinspection PhpIncludeInspection */
            $environmentConfig = require(APP_ROOT . DIRECTORY_SEPARATOR . 'config.' . $environment . '.php');
            $this->config = Utils::arrayMerge($this->config, $environmentConfig);
        }

        $this->initPdo();
        $this->initRouter();
    }

    public function run()
    {
        $this->request = new Request();
        $this->response = $this->router->run($this->request);
        $this->response->send();
    }

    private function initPdo()
    {
        $this->pdo = function(Application $application) {
            $config = isset($application->config) && isset($application->config['pdo']) ? $application->config['pdo'] : null;
            if (!$config) {
                throw new Exception('Invalid pdo configuration.');
            }

            $config['username'] = isset($config['username']) ? $config['username'] : '';
            $config['passwd'] = isset($config['passwd']) ? $config['passwd'] : '';
            $config['options'] = isset($config['options']) ? $config['options'] : '';

            return new PDO($config['dsn'], $config['username'], $config['passwd'], $config['options']);
        };
    }

    private function initRouter()
    {
        $this->router = function(Application $application) {
            $config = isset($application->config) ? $application->config : [];
            $routes = isset($config['routes']) ? $config['routes'] : null;
            $defaultRoute = isset($config['defaultRoute']) ? $config['defaultRoute'] : null;
            $controllerNamespace = isset($config['controllerNamespace']) ? $config['controllerNamespace'] : null;

            return new Router($routes, $defaultRoute, $controllerNamespace);
        };
    }
}