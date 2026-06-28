<?php

class Request
{
    public static function method(): string
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    public static function path(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';

        $path = parse_url($uri, PHP_URL_PATH);

        // remove trailing slash
        $path = rtrim($path, '/');

        // remove index.php nếu có
        $path = str_replace('/index.php', '', $path);

        return $path === '' ? '/' : $path;
    }
}