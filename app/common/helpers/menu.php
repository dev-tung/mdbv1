<?php

/**
 * Active menu theo URL hiện tại.
 */
function active_menu(string $keyword): string
{
    $current = $_SERVER['REQUEST_URI'] ?? '';

    return str_contains($current, $keyword)
        ? 'active'
        : '';
}

/**
 * Active menu theo URL chính xác.
 */
function active_menu_exact(string $url): string
{
    $current = rtrim($_SERVER['REQUEST_URI'] ?? '/', '/');
    $url = rtrim($url, '/');

    return $current === $url
        ? 'active'
        : '';
}
