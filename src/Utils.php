<?php

namespace memclutter\PhpTodo;

class Utils
{
    /**
     * @param $a
     * @param $b
     * @return mixed
     */
    public static function arrayMerge($a, $b)
    {
        $args = func_get_args();
        $arr = array_shift($args);
        while (!empty($args)) {
            $next = array_shift($args);
            foreach ($next as $k => $v) {
                if (is_int($k)) {
                    if (isset($arr[$k])) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($arr[$k]) && is_array($arr[$k])) {
                    $arr[$k] = self::arrayMerge($arr[$k], $v);
                } else {
                    $arr[$k] = $v;
                }
            }
        }

        return $arr;
    }
}