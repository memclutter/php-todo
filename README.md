# TODO 
The application is written in pure PHP. Fuck frameworks! Pure PHP only!
## Installation
Clone git-repository, change directory to project root and run composer install.
```sh
git clone git@github.com:memclutter/php-todo.git 
cd php-todo
composer install
```
Create PDO-compatibility database and create local config file, placed in config.local.php
```php
<?php

return [
    'pdo' => [
        'dsn' => 'mysql:dbname=todo;host=localhost;charset=utf8',
        'username' => 'todo',
        'passwd' => 'secret',
    ],
];
```
Run local webserver.
```sh
php -t www -S localhost:8080
```
See http://localhost:8080.