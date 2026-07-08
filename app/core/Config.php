<?php

class Config
{
    private static array $cache = [];

    public static function get(string $file, string $key = null, $default = null)
    {
        if (!isset(self::$cache[$file])) {
            self::$cache[$file] = require __DIR__ . "/../config/{$file}.php";
        }

        if ($key === null) {
            return self::$cache[$file];
        }

        return self::$cache[$file][$key] ?? $default;
    }
}
