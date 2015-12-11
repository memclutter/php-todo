<?php
/* @var $this \memclutter\PhpTodo\Template */

use memclutter\PhpTodo\Todo;

$statusLabels = Todo::statusLabels();
$priorityLabels = Todo::priorityLabels();
?>
<div class="panel panel-success">
    <div class="panel-heading">create todo</div>
    <div class="panel-body">
        <form role="form" class="form-horizontal" method="post">
            <div class="form-group">
                <label for="text" class="col-sm-2 control-label">Text</label>
                <div class="col-sm-10">
                    <input type="text" name="text" placeholder="Text" class="form-control">
                </div>
            </div>
            <div class="form-group">
                <label for="status" class="col-sm-2 control-label">Status</label>
                <div class="col-sm-10">
                    <select name="status" class="form-control">
                        <?php foreach ($statusLabels as $status => $statusLabel): ?>
                            <option value="<?= $status ?>"><?= $statusLabel ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="priority" class="col-sm-2 control-label">Priority</label>
                <div class="col-sm-10">
                    <select name="priority" class="form-control">
                        <?php foreach ($priorityLabels as $priority => $priorityLabel): ?>
                            <option value="<?= $priority ?>"><?= $priorityLabel ?></option>
                        <?php endforeach ?>
                    </select>
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