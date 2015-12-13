<?php

use memclutter\PhpTodo\Application;

require_once 'vendor/autoload.php';

define('APP_ROOT', __DIR__);

$application = Application::getInstance();

if (file_exists(APP_ROOT . DIRECTORY_SEPARATOR . 'config.test.php')) {
    $application->init('test');
} else {
    $application->init();
}

