<?php
/* @var $this \memclutter\PhpTodo\Template */
/* @var $todoList \memclutter\PhpTodo\Todo[] */
?>
<h1>todo list</h1>
<ul>
    <?php foreach ($todoList as $todo): ?>
        <li><a href="<?= $this->getApplication()->router->reverse('todoItem', [':id' => $todo->id]) ?>">#<?= $todo->id ?>: <?= $todo->text ?></a></li>
    <?php endforeach ?>
</ul>


