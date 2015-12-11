<?php
/* @var $this \memclutter\PhpTodo\Template */
/* @var $item \memclutter\PhpTodo\Todo */
?>
<div class="panel panel-success">
    <div class="panel-heading"><?= $item->text ?></div>
    <div class="panel-body">
        <a class="btn btn-primary" href="<?= $this->application()->router->reverse('todoIndex') ?>">
            Back
        </a>
        <a class="btn btn-success" href="<?= $this->application()->router->reverse('todoUpdate', ['id' => $item->id]) ?>">
            Update
        </a>
        <a class="btn btn-danger" href="<?= $this->application()->router->reverse('todoDelete', ['id' => $item->id]) ?>">
            Delete
        </a>
    </div>
    <table class="table">
        <tr>
            <th>ID</th>
            <td><?= $item->id ?></td>
        </tr>
        <tr>
            <th>Text</th>
            <td><?= $item->text ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= $item->statusLabels($item->status) ?></td>
        </tr>
        <tr>
            <th>Priority</th>
            <td><?= $item->priorityLabels($item->priority) ?></td>
        </tr>
        <tr>
            <th>Created</th>
            <td><?= $item->created ?></td>
        </tr>
        <tr>
            <th>Updated</th>
            <td><?= $item->updated ?></td>
        </tr>
    </table>
</div>