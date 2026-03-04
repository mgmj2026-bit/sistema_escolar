<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;

final class ChatMessage
{
    public function latest(int $limit = 50): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT cm.id, cm.body, cm.created_at, u.name FROM chat_messages cm INNER JOIN users u ON u.id = cm.user_id ORDER BY cm.id DESC LIMIT :limit'
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return array_reverse($stmt->fetchAll());
    }

    public function create(int $userId, string $body): void
    {
        $stmt = Database::connection()->prepare('INSERT INTO chat_messages (user_id, body) VALUES (:user_id, :body)');
        $stmt->execute([
            'user_id' => $userId,
            'body' => $body,
        ]);
    }

    public function since(int $lastId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT cm.id, cm.body, cm.created_at, u.name FROM chat_messages cm INNER JOIN users u ON u.id = cm.user_id WHERE cm.id > :last_id ORDER BY cm.id ASC'
        );
        $stmt->execute(['last_id' => $lastId]);
        return $stmt->fetchAll();
    }
}
