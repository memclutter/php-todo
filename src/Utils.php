<?php

namespace memclutter\PhpTodo;

class Utils
{
    /**
     * @param $path
     * @return string
     */
    public static function normalizeFilePath($path)
    {
        $path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
        return trim($path, DIRECTORY_SEPARATOR);
    }

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
                        $arr[] = $v;
                    } else {
                        $arr[$k] = $v;
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

    public static function getClientIp()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}