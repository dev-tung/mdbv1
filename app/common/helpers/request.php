<?php

/**
 * Lấy 1 input từ request (GET/POST)
 */
function request_input(string $key, mixed $default = null): mixed
{
    return $_REQUEST[$key] ?? $default;
}

/**
 * Lấy toàn bộ request data (GET + POST)
 */
function request_all(): array
{
    return $_REQUEST;
}

/**
 * Lấy filters từ request (chỉ lấy key cần thiết + bỏ rỗng)
 */
function request_filters(array $keys): array
{
    $filters = [];

    foreach ($keys as $key) {
        $value = $_REQUEST[$key] ?? null;

        if ($value !== null && $value !== '') {
            $filters[$key] = $value;
        }
    }

    return $filters;
}

/**
 * Validate & lấy ID (dùng chung toàn hệ thống)
 */
function request_id(string $key = 'id'): int
{
    $id = (int) ($_REQUEST[$key] ?? 0);

    if ($id <= 0) {
        throw new InvalidArgumentException('ID không hợp lệ');
    }

    return $id;
}

/**
 * Check request method
 */
function request_method(): string
{
    return $_SERVER['REQUEST_METHOD'] ?? 'GET';
}

/**
 * Check POST
 */
function is_post(): bool
{
    return request_method() === 'POST';
}

/**
 * Check GET
 */
function is_get(): bool
{
    return request_method() === 'GET';
}