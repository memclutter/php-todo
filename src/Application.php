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
 * @property Layout $layout
 * @property Logger $logger
 * @property Initializer $initializer
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

        $this->initializer = new Initializer();
        $this->logger = [$this->initializer, 'logger'];
        $this->pdo = [$this->initializer, 'pdo'];
        $this->router = [$this->initializer, 'router'];
        $this->layout = [$this->initializer, 'layout'];
    }

    public function run()
    {
        $this->logger->i('APPLICATION', 'Application run');
        $this->request = new Request();
        $this->response = $this->router->run($this->request);
        $this->response->send();
    }
}