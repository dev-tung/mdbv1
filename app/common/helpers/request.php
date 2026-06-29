<?php

/**
 * Lấy 1 input từ request (GET/POST)
 */
function request_input(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

/**
 * Lấy toàn bộ request data (GET + POST)
 * KHÔNG dùng $_REQUEST để tránh COOKIE
 */
function request_all(): array
{
    $json = json_decode(file_get_contents("php://input"), true);

    if (is_array($json)) {
        return $json;
    }

    return $_POST + $_GET;
}

/**
 * Lấy filters từ request (chỉ lấy key cần thiết + bỏ rỗng)
 */
function request_filters(array $keys): array
{
    $data = request_all();
    $filters = [];

    foreach ($keys as $key) {
        $value = $data[$key] ?? null;

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
    $data = request_all();

    $id = (int) ($data[$key] ?? 0);

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