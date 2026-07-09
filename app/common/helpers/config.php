<?php

function config($key)
{
	static $cache = [];

	if (isset($cache[$key])) {
		return $cache[$key];
	}

	$parts = explode('.', $key);

	// module: shop.option.purchase_status
	$module = array_shift($parts); // shop
	$file = array_shift($parts); // option

	$path = BASE_PATH . "/app/modules/{$module}/config/{$file}.php";

	if (!file_exists($path)) {
		return null;
	}

	$data = require $path;

	// nếu không có deeper key
	if (empty($parts)) {
		return $cache[$key] = $data;
	}

	// dot access sâu hơn
	foreach ($parts as $p) {
		if (!isset($data[$p])) {
			return null;
		}
		$data = $data[$p];
	}

	return $cache[$key] = $data;
}
