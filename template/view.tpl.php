<?php
/* @var $this \memclutter\PhpTodo\Template */
/* @var $item \memclutter\PhpTodo\Todo */
?>
<h1><?= $item->text ?></h1>

<p>
    <span>Created: <?= $item->created ?></span><br>
    <span>Updated: <?= $item->updated ?></span>
</p>

<p>
    <span>Status: <?= $item->status ?></span><br>
    <span>Priority: <?= $item->priority ?></span>
</p>