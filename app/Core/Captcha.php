<?php

declare(strict_types=1);

namespace App\Core;

final class Captcha
{
    public static function generar(): array
    {
        $chars = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $codigo = '';
        for ($i = 0; $i < 6; $i++) {
            $codigo .= $chars[random_int(0, strlen($chars) - 1)];
        }
        $_SESSION['_captcha'] = $codigo;
        return ['codigo' => $codigo];
    }

    public static function validar(string $input): bool
    {
        return strtoupper(trim($input)) === strtoupper((string) ($_SESSION['_captcha'] ?? ''));
    }
}
