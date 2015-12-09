<?php

return [
    'pdo' => [
        'dsn' => 'mysql:dbname=todo;host=localhost;charset=utf8',
        'username' => 'root',
        'options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ],
    ],
    'templateDir' => APP_ROOT . DIRECTORY_SEPARATOR . 'template',
];