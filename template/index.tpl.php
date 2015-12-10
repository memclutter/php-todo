<?php
/* @var $this \memclutter\PhpTodo\Template */
/* @var $items \memclutter\PhpTodo\Todo[] */
?>
<h1>todo list</h1>
<a href="<?= $this->application()->router->reverse('todoCreate') ?>">Create new todo</a>
<ul>
    <?php foreach ($items as $item): ?>
        <li><a href="<?= $this->application()->router->reverse('todoView', ['id' => $item->id]) ?>"><?= $item->text ?></a></li>
    <?php endforeach ?>
</ul>


