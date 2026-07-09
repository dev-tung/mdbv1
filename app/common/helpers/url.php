<?php

function base_url(string $path = ''): string
{
	$base = Env::get('APP_URL', 'http://localhost:8000');

	return rtrim($base, '/') . '/' . ltrim($path, '/');
}

function current_url(): string
{
	return $_SERVER['REQUEST_URI'] ?? '/';
}

function asset(string $path): string
{
	return base_url('assets/' . ltrim($path, '/'));
}

function upload(string $path): string
{
	return base_url('uploads/' . ltrim($path, '/'));
}

function route(string $path = ''): string
{
	return base_url($path);
}

function redirect_url(string $url): void
{
	header("Location: {$url}");
	exit();
}

function previous_url(string $default = '/'): string
{
	return $_SERVER['HTTP_REFERER'] ?? $default;
}
