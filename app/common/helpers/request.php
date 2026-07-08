<?php

/**
 * Lấy 1 input từ request (GET/POST)
 */
function request_input(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $_GET[$key] ?? $default;
}

/**
 * Lấy toàn bộ request data (GET + POST + JSON BODY)
 * + AUTO MERGE ID từ URL
 */
function request_all(): array
{
    // =========================
    // 1. JSON BODY
    // =========================
    $json = json_decode(file_get_contents('php://input'), true);

    $data = [];

    if (is_array($json)) {
        $data = $json;
    } else {
        // =========================
        // 2. FORM / QUERY
        // =========================
        $data = $_POST + $_GET;
    }

    // =========================
    // 3. AUTO GET ID FROM URL
    // =========================
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $uri = parse_url($uri, PHP_URL_PATH);

    $segments = explode('/', trim($uri, '/'));
    $lastSegment = end($segments);

    if (is_numeric($lastSegment)) {
        if (!isset($data['id'])) {
            $data['id'] = (int) $lastSegment;
        }
    }

    return $data;
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

    if (!empty($data[$key]) && (int) $data[$key] > 0) {
        return (int) $data[$key];
    }

    throw new InvalidArgumentException('ID không hợp lệ');
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
