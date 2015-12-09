<?php

use memclutter\PhpTodo\Template;
use memclutter\PhpTodo\Todo;

return [
    'pdo' => [
        'dsn' => 'mysql:dbname=todo;host=localhost;charset=utf8',
        'username' => 'root',
        'options' => [
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        ],
    ],
    'templateDir' => APP_ROOT . DIRECTORY_SEPARATOR . 'template',
    'router' => [
        'defaultRoute' => 'todo',
        'rules' => [
            [
                'name' => 'todoList',
                'pattern' => 'todo',
                'templateFile' => 'index.tpl.php',
                'container' => [
                    'todoList' => function() {
                        return Todo::findAll();
                    },
                ],
            ],
            [
                'name' => 'todoItem',
                'pattern' => 'todo/:id',
                'templateFile' => 'todo.tpl.php',
                'container' => [
                    'todo' => function(Template $template) {
                        return Todo::find($template->get('id'));
                    },
                ]
            ]
        ],
    ],
];