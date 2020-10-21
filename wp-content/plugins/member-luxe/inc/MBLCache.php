<?php

class MBLCache
{
    private static $storage = array();

    public static function get($key) {
        if (isset(self::$storage[$key])) {
            return self::$storage[$key];
        }

        return null;
    }

    public static function set($key, $value)
    {
        self::$storage[$key] = $value;
    }
}