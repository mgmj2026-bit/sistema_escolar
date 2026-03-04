<?php

declare(strict_types=1);

namespace App\Core;

final class Audit
{
    public static function log(?int $userId, string $evento, string $detalle = ''): void
    {
        $db = Database::connection();
        $stmt = $db->prepare('INSERT INTO audit_logs (user_id, evento, detalle, ip) VALUES (:user_id,:evento,:detalle,:ip)');
        $stmt->execute([
            'user_id' => $userId,
            'evento' => $evento,
            'detalle' => $detalle,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'N/A',
        ]);
    }
}
