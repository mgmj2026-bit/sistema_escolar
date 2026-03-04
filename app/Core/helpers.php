<?php

declare(strict_types=1);

function env(string $key, mixed $default = null): mixed
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    return $value === false || $value === null ? $default : $value;
}

function base_path(string $path = ''): string
{
    $root = dirname(__DIR__, 2);
    return $path === '' ? $root : $root . '/' . ltrim($path, '/');
}

function app_base_url(): string
{
    $base = (string) ($_SERVER['APP_BASE_URL'] ?? env('APP_BASE_URL', ''));
    $base = trim($base);
    if ($base === '' || $base === '/') {
        return '';
    }
    return '/' . trim($base, '/');
}

function app_url(string $path = '/'): string
{
    $base = app_base_url();
    if ($path === '') {
        return $base === '' ? '/' : $base;
    }

    $path = '/' . ltrim($path, '/');
    return ($base === '' ? '' : $base) . $path;
}

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
