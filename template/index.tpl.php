<?php
/* @var $this \memclutter\PhpTodo\Template */
/* @var $items \memclutter\PhpTodo\Todo[] */
?>
<div class="panel panel-success">
    <div class="panel-heading">todo list</div>
    <div class="panel-body">
        <a class="btn btn-success" href="<?= $this->application()->router->reverse('todoCreate') ?>">Create new todo</a>
    </div>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Text</th>
            <th>Status</th>
            <th>Priority</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= $item->id ?></td>
                <td><?= $item->text ?></td>
                <td><?= $item->statusLabels($item->status) ?></td>
                <td><?= $item->priorityLabels($item->priority) ?></td>
                <td>
                    <a title="View" href="<?= $this->application()->router->reverse('todoView', ['id' => $item->id]) ?>">
                        <span class="glyphicon glyphicon-eye-open text-success"></span>
                    </a>
                    <a title="Update" href="<?= $this->application()->router->reverse('todoUpdate', ['id' => $item->id]) ?>">
                        <span class="glyphicon glyphicon-pencil text-success"></span>
                    </a>
                    <a title="Delete" href="<?= $this->application()->router->reverse('todoDelete', ['id' => $item->id]) ?>">
                        <span class="glyphicon glyphicon-remove text-danger"></span>
                    </a>
                </td>
            </tr>
        <?php endforeach ?>
    </table>
</div>
