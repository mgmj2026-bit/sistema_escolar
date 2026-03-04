<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    public static function attemptPassword(string $email, string $password): ?array
    {
        $user = (new User())->findByEmail($email);
        if (!$user || (int) $user['is_verified'] !== 1 || !Security::verifyPassword($password, $user['password_hash'])) {
            return null;
        }
        return $user;
    }

    public static function loginUser(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
        ];
    }

    public static function user(): ?array { return $_SESSION['user'] ?? null; }
    public static function check(): bool { return isset($_SESSION['user']); }
    public static function requireAuth(): void { if (!self::check()) { header('Location: /login'); exit; } }

    public static function requireRoles(array $roles): void
    {
        self::requireAuth();
        if (!in_array((string) ($_SESSION['user']['role'] ?? ''), $roles, true)) { http_response_code(403); exit('No autorizado.'); }
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
        }
        session_destroy();
    }
}
