<?php
/* @var $this \memclutter\PhpTodo\Template */
/* @var $caption string */
/* @var $item \memclutter\PhpTodo\Todo|null */
/* @var $values array */
/* @var $errors array */

use memclutter\PhpTodo\Todo;

$statusLabels = Todo::statusLabels();
$priorityLabels = Todo::priorityLabels();

$values['text'] = isset($values['text']) ? $values['text'] : ($item ? $item->text : null);
$values['status'] = isset($values['status']) ? $values['status'] : ($item ? $item->status : null);
$values['priority'] = isset($values['priority']) ? $values['priority'] : ($item ? $item->priority : null);

$errors['text'] = isset($errors['text']) ? "<span class=\"text-danger\">{$errors['text']}</span>" : '';
$errors['status'] = isset($errors['status']) ? "<span class=\"text-danger\">{$errors['status']}</span>" : '';
$errors['priority'] = isset($errors['priority']) ? "<span class=\"text-danger\">{$errors['priority']}</span>" : '';

$hasClass['text'] = !empty($errors['text']) ? ' has-error' : ($values['text'] !== null ? ' has-success' : '');
$hasClass['status'] = !empty($errors['status']) ? ' has-error' : ($values['status'] !== null ? ' has-success' : '');
$hasClass['priority'] = !empty($errors['priority']) ? ' has-error' : ($values['priority'] !== null ? ' has-success' : '');
?>
<div class="panel panel-success">
    <div class="panel-heading"><?= $caption ?></div>
    <div class="panel-body">
        <form role="form" class="form-horizontal" method="post">
            <?php if ($item): ?>
                <input type="hidden" name="id" value="<?= $item->id ?>">
            <?php endif ?>
            <div class="form-group<?= $hasClass['text'] ?>">
                <label for="text" class="col-sm-2 control-label">Text</label>
                <div class="col-sm-10">
                    <input type="text" id="text" name="text" placeholder="Text" class="form-control" value="<?= $values['text'] ?>">
                    <?= $errors['text'] ?>
                </div>
            </div>
            <div class="form-group<?= $hasClass['status'] ?>">
                <label for="status" class="col-sm-2 control-label">Status</label>
                <div class="col-sm-10">
                    <select id="status" name="status" class="form-control">
                        <?php foreach ($statusLabels as $status => $statusLabel): ?>
                            <?php $selected = ($status == $values['status']) ? ' selected' : '' ?>
                            <option value="<?= $status ?>"<?= $selected ?>><?= $statusLabel ?></option>
                        <?php endforeach ?>
                    </select>
                    <?= $errors['status'] ?>
                </div>
            </div>
            <div class="form-group<?= $hasClass['priority'] ?>">
                <label for="priority" class="col-sm-2 control-label">Priority</label>
                <div class="col-sm-10">
                    <select id="priority" name="priority" class="form-control">
                        <?php foreach ($priorityLabels as $priority => $priorityLabel): ?>
                            <?php $selected = ($priority == $values['priority']) ? ' selected' : '' ?>
                            <option value="<?= $priority ?>"<?= $selected ?>><?= $priorityLabel ?></option>
                        <?php endforeach ?>
                    </select>
                    <?= $errors['priority'] ?>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                    <input type="reset" class="btn btn-default" value="Reset">
                    <input type="submit" class="btn btn-default" value="Create">
                </div>
            </div>
        </form>
    </div>
</div>