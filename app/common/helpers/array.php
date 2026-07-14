<?php

if (!function_exists('array_params')) {
	function array_params(array $keys, array $data): array
	{
		return array_map(function ($key) use ($data) {
			$value = $data[$key] ?? null;

			return $value === '' ? null : $value;
		}, $keys);
	}
}
