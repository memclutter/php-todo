<?php

namespace memclutter\PhpTodo;

/**
 * Class Application
 *
 * @property Request $request
 * @property Response $response
 * @property array $config
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
    }

    public function run()
    {
        $this->request = new Request();
        $path = $this->request->getPath();
        $content = sprintf('application run with path "%s".<br>config data <pre>%s</pre>', $path, print_r($this->config, true));
        $this->response = new Response(200, $content);
        $this->response->send();
    }
}