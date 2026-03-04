<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class ChatMessage
{
    public function conversacion(int $a, int $b, int $limit = 80): array
    {
        $stmt = Database::connection()->prepare('SELECT cm.id, cm.body, cm.created_at, cm.sender_id, us.name AS remitente
            FROM chat_messages cm
            INNER JOIN users us ON us.id = cm.sender_id
            WHERE (cm.sender_id = :a AND cm.receiver_id = :b) OR (cm.sender_id = :b AND cm.receiver_id = :a)
            ORDER BY cm.id DESC LIMIT :limit');
        $stmt->bindValue(':a', $a, \PDO::PARAM_INT);
        $stmt->bindValue(':b', $b, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return array_reverse($stmt->fetchAll());
    }

    public function enviar(int $senderId, int $receiverId, string $body): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO chat_messages (sender_id, receiver_id, body) VALUES (:s, :r, :b)');
        $stmt->execute(['s' => $senderId, 'r' => $receiverId, 'b' => $body]);
    }

    public function desde(int $a, int $b, int $lastId): array
    {
        $stmt = Database::connection()->prepare('SELECT cm.id, cm.body, cm.created_at, cm.sender_id, us.name AS remitente
            FROM chat_messages cm
            INNER JOIN users us ON us.id = cm.sender_id
            WHERE (((cm.sender_id = :a AND cm.receiver_id = :b) OR (cm.sender_id = :b AND cm.receiver_id = :a)))
            AND cm.id > :last_id ORDER BY cm.id ASC');
        $stmt->execute(['a' => $a, 'b' => $b, 'last_id' => $lastId]);
        return $stmt->fetchAll();
    }
}
