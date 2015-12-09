<?php

namespace memclutter\PhpTodo;

/**
 * Class Todo
 *
 * @property int $id
 * @property string $text
 * @property int $status
 * @property int $priority
 * @property string $created
 * @property string $updated
 */
class Todo
{
    use ActiveRecordTrait;
}