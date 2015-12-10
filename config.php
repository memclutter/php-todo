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
    'defaultRoute' => 'todo',
    'controllerNamespace' => 'controller\\',
    'routes' => [
        'todoIndex' => [
            'pattern' => 'todo',
            'controller' => 'todo',
        ],
        'todoView' => [
            'pattern' => 'todo/:id',
            'controller' => 'todo',
            'action' => 'view',
        ],
        'todoCreate' => [
            'pattern' => 'todo/create',
            'controller' => 'todo',
            'action' => 'create',
        ],
        'todoUpdate' => [
            'pattern' => 'todo/:id/update',
            'controller' => 'todo',
            'action' => 'update',
        ],
        'todoDelete' => [
            'pattern' => 'todo/:id/delete',
            'controller' => 'todo',
            'action' => 'delete',
        ],
    ],
];