<?php

declare(strict_types=1);

namespace App\Core;

final class Security
{
    public static function applyDefaultHeaders(array $securityConfig): void
    {
        foreach ($securityConfig['headers'] as $header => $value) {
            header($header . ': ' . $value);
        }
    }

    public static function hashPassword(string $password): string
    {
        $algo = defined('PASSWORD_ARGON2ID') ? PASSWORD_ARGON2ID : PASSWORD_BCRYPT;
        return password_hash($password, $algo);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function requireCsrfToken(string $token): bool
    {
        return hash_equals($_SESSION['_csrf'] ?? '', $token);
    }

    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }
}
