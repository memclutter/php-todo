<?php
/* @var $this \memclutter\PhpTodo\Layout */
?>
<!DOCTYPE html>
<html>
<head>
    <title><?= $this->title() ?></title>
    <meta charset="<?= $this->charset() ?>">
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>

    <?= $this->content ?>

    <?php $this->endBody() ?>
</body>
</html>
