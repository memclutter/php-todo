<?php
/* @var $this \memclutter\PhpTodo\Template */
/* @var $items \memclutter\PhpTodo\Todo[] */
?>
<h1>todo list</h1>
<ul>
    <?php foreach ($items as $item): ?>
        <li>#<?= $item->id ?>: <?= $item->text ?></li>
    <?php endforeach ?>
</ul>


