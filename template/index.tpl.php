<?php
/* @var $this \memclutter\PhpTodo\Template */
/* @var $items \memclutter\PhpTodo\Todo[] */
?>
<h1>todo list</h1>
<ul>
    <?php foreach ($items as $item): ?>
        <li><a href="<?= $this->getApplication()->router->reverse('todoView', ['id' => $item->id]) ?>"><?= $item->text ?></a></li>
    <?php endforeach ?>
</ul>


