<?php
function request_filters(array $keys): array
{
    $filters = [];

    foreach ($keys as $key) {
        $value = $_GET[$key] ?? null;

        if ($value !== null && $value !== '') {
            $filters[$key] = $value;
        }
    }

    return $filters;
}