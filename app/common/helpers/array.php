<?php

if (!function_exists('array_params')) {
	function array_params(array $keys, array $data): array
	{
		return array_map(fn($key) => $data[$key] ?? null, $keys);
	}
}
