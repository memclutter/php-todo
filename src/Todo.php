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

    const STATUS_NEW = 0;
    const STATUS_ACTIVE = 2;
    const STATUS_FINISH = 4;

    const PRIORITY_LOW = 0;
    const PRIORITY_NORMAL = 2;
    const PRIORITY_HIGH = 4;
    const PRIORITY_CRITICAL = 8;

    /**
     * @param null|int $status
     * @return array
     * @throws Exception
     */
    public static function statusLabels($status = null)
    {
        $statusLabels = [
            self::STATUS_NEW => 'New',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_FINISH => 'Finish',
        ];

        if ($status !== null) {
            if (!isset($statusLabels[$status])) {
                throw new Exception("Status label not found, status value is '{$status}'.'");
            }

            return $statusLabels[$status];
        }

        return $statusLabels;
    }

    /**
     * @param null $priority
     * @return array
     * @throws Exception
     */
    public static function priorityLabels($priority = null)
    {
        $priorityLabels = [
            self::PRIORITY_LOW => 'Low',
            self::PRIORITY_NORMAL => 'Normal',
            self::PRIORITY_HIGH => 'High',
            self::PRIORITY_CRITICAL => 'Critical',
        ];

        if ($priority !== null) {
            if (!isset($priorityLabels[$priority])) {
                throw new Exception("Priority label not found, priority value is '{$priority}'.");
            }

            return $priorityLabels[$priority];
        }

        return $priorityLabels;
    }
}