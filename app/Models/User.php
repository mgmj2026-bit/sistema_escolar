<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use App\Core\Security;

final class User
{
    public function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }

    public function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        return $stmt->fetch() ?: null;
    }

    public function crear(array $data): int
    {
        $token = bin2hex(random_bytes(24));
        $stmt = Database::connection()->prepare('INSERT INTO users (name, email, role, password_hash, is_verified, verification_token) VALUES (:name, :email, :role, :password_hash, 0, :token)');
        $stmt->execute([
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'password_hash' => Security::hashPassword($data['password']),
            'token' => $token,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function verificarPorToken(string $token): ?array
    {
        $stmt = Database::connection()->prepare('SELECT * FROM users WHERE verification_token = :token AND is_verified = 0 LIMIT 1');
        $stmt->execute(['token' => $token]);
        $user = $stmt->fetch();
        if (!$user) {
            return null;
        }

        $update = Database::connection()->prepare('UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = :id');
        $update->execute(['id' => $user['id']]);
        return $user;
    }

    public function cambiarContrasena(int $userId, string $nuevaClave): void
    {
        $stmt = Database::connection()->prepare('UPDATE users SET password_hash = :password_hash WHERE id = :id');
        $stmt->execute([
            'password_hash' => Security::hashPassword($nuevaClave),
            'id' => $userId,
        ]);
    }

    public function contactosPermitidos(array $usuario): array
    {
        $role = $usuario['role'];
        $id = (int) $usuario['id'];
        $db = Database::connection();

        $allRoles = ['director', 'subdirector', 'secretaria', 'auxiliar', 'docente', 'estudiante', 'padre'];
        $permitidos = [];

        if (in_array($role, ['director', 'subdirector', 'secretaria', 'auxiliar'], true)) {
            $stmt = $db->prepare('SELECT id, name, role FROM users WHERE id <> :id AND is_verified = 1 ORDER BY role, name');
            $stmt->execute(['id' => $id]);
            return $this->agruparPorRol($stmt->fetchAll());
        }

        if ($role === 'docente') {
            $stmt = $db->prepare("SELECT id, name, role FROM users WHERE id <> :id AND is_verified = 1 AND role IN ('docente','director','secretaria','auxiliar')");
            $stmt->execute(['id' => $id]);
            $permitidos = $stmt->fetchAll();

            $est = $db->prepare('SELECT DISTINCT u.id, u.name, u.role
                FROM users u
                INNER JOIN enrollments e ON e.student_id = u.id
                INNER JOIN teacher_sections ts ON ts.section_id = e.section_id
                WHERE ts.teacher_id = :id AND u.role = "estudiante" AND u.is_verified = 1');
            $est->execute(['id' => $id]);
            $permitidos = array_merge($permitidos, $est->fetchAll());

            return $this->agruparPorRol($permitidos);
        }

        if ($role === 'estudiante') {
            $stmt = $db->prepare("SELECT DISTINCT u.id, u.name, u.role
                FROM users u
                WHERE u.is_verified = 1 AND u.id <> :id AND u.role IN ('auxiliar', 'subdirector')
                UNION
                SELECT DISTINCT d.id, d.name, d.role
                FROM users d
                INNER JOIN teacher_sections ts ON ts.teacher_id = d.id
                INNER JOIN enrollments e ON e.section_id = ts.section_id
                WHERE e.student_id = :id AND d.role = 'docente' AND d.is_verified = 1");
            $stmt->execute(['id' => $id]);
            return $this->agruparPorRol($stmt->fetchAll());
        }

        if ($role === 'padre') {
            $stmt = $db->prepare("SELECT id, name, role FROM users WHERE role IN ('subdirector','auxiliar','secretaria') AND is_verified = 1
                UNION
                SELECT DISTINCT d.id, d.name, d.role
                FROM users d
                INNER JOIN teacher_sections ts ON ts.teacher_id = d.id
                INNER JOIN enrollments e ON e.section_id = ts.section_id
                INNER JOIN parent_students ps ON ps.student_id = e.student_id
                WHERE ps.parent_id = :id AND d.role = 'docente' AND d.is_verified = 1");
            $stmt->execute(['id' => $id]);
            return $this->agruparPorRol($stmt->fetchAll());
        }

        return array_fill_keys($allRoles, []);
    }

    public function seccionesDocente(int $teacherId): array
    {
        $stmt = Database::connection()->prepare('SELECT s.id, CONCAT(g.name, " - ", s.name) AS nombre
            FROM teacher_sections ts
            INNER JOIN sections s ON s.id = ts.section_id
            INNER JOIN grades g ON g.id = s.grade_id
            WHERE ts.teacher_id = :id ORDER BY g.name, s.name');
        $stmt->execute(['id' => $teacherId]);
        return $stmt->fetchAll();
    }

    public function estudiantesPorSeccionDocente(int $teacherId, int $sectionId): array
    {
        $stmt = Database::connection()->prepare('SELECT u.id, u.name
            FROM users u
            INNER JOIN enrollments e ON e.student_id = u.id
            INNER JOIN teacher_sections ts ON ts.section_id = e.section_id
            WHERE ts.teacher_id = :teacher_id AND e.section_id = :section_id AND u.role = "estudiante" AND u.is_verified = 1
            ORDER BY u.name');
        $stmt->execute(['teacher_id' => $teacherId, 'section_id' => $sectionId]);
        return $stmt->fetchAll();
    }

    private function agruparPorRol(array $usuarios): array
    {
        $grupos = [
            'director' => [],
            'subdirector' => [],
            'secretaria' => [],
            'auxiliar' => [],
            'docente' => [],
            'estudiante' => [],
            'padre' => [],
        ];

        foreach ($usuarios as $u) {
            if (isset($grupos[$u['role']])) {
                $grupos[$u['role']][] = $u;
            }
        }

        return $grupos;
    }
}
