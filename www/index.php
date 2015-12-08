<?php

use memclutter\PhpTodo\Application;

require_once '../vendor/autoload.php';

$application = Application::getInstance();
$application->run();