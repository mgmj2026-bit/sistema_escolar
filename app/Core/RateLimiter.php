<?php

declare(strict_types=1);

namespace App\Core;

final class RateLimiter
{
    public static function hit(string $key, int $maxAttempts, int $windowSeconds): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'cli';
        $bucket = '_rl_' . sha1($key . '|' . $ip);
        $now = time();
        $data = $_SESSION[$bucket] ?? ['count' => 0, 'start' => $now];

        if (($now - (int) $data['start']) > $windowSeconds) {
            $data = ['count' => 0, 'start' => $now];
        }

        $data['count']++;
        $_SESSION[$bucket] = $data;

        return (int) $data['count'] <= $maxAttempts;
    }
}
