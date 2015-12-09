<?php
/* @var $this \memclutter\PhpTodo\Template */
/* @var $todoList \memclutter\PhpTodo\Todo[] */
?>
<h1>todo list</h1>
<ul>
    <?php foreach ($todoList as $todo): ?>
        <li>#<?= $todo->id ?>: <?= $todo->text ?></li>
    <?php endforeach ?>
</ul>


