<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use PDO;
use App\Domain\Entities\User;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Database\UserMapper;

final class MySQLUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly PDO $db
    ) {
    }

    public function findByUsername(string $username): ?User
    {
        $stmt = $this->db->prepare("
            SELECT
                u.*,
                r.name AS role_name
            FROM users u
            INNER JOIN roles r
                ON r.id = u.role_id
            WHERE u.username = :username
            LIMIT 1
        ");

        $stmt->execute([
            'username' => $username
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return UserMapper::map($row);
    }

    public function findById(int $id): ?User
    {
        $stmt = $this->db->prepare("
            SELECT
                u.*,
                r.name AS role_name
            FROM users u
            INNER JOIN roles r
                ON r.id = u.role_id
            WHERE u.id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return UserMapper::map($row);
    }

    public function updateLastLogin(int $userId): void
    {
        $stmt = $this->db->prepare("
            UPDATE users
            SET last_login = NOW()
            WHERE id = :id
        ");

        $stmt->execute([
            'id' => $userId
        ]);
    }
}