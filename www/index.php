<?php

use memclutter\PhpTodo\Application;

require_once '../vendor/autoload.php';

define('APP_ROOT', dirname(__DIR__));

$application = Application::getInstance();

if (file_exists(APP_ROOT . DIRECTORY_SEPARATOR . 'config.local.php')) {
    $application->init('local');
} else {
    $application->init();
}

$application->run();