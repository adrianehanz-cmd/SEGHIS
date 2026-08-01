<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Auth;

use App\Domain\Repositories\Auth\UserRepositoryInterface;
use App\Infrastructure\Database\Database;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly Database $database
    ) {
    }

    public function findByUsername(string $username): ?array
    {
        $statement = $this->database->connection()->prepare(
            'SELECT u.*, r.name AS role_name
             FROM users u
             INNER JOIN roles r ON r.id = u.role_id
             WHERE u.username = :username
               AND u.deleted_at IS NULL
             LIMIT 1'
        );

        $statement->execute(['username' => $username]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function findById(int $id): ?array
    {
        $statement = $this->database->connection()->prepare(
            'SELECT u.*, r.name AS role_name
             FROM users u
             INNER JOIN roles r ON r.id = u.role_id
             WHERE u.id = :id
               AND u.deleted_at IS NULL
             LIMIT 1'
        );

        $statement->execute(['id' => $id]);

        $user = $statement->fetch();

        return $user ?: null;
    }

    public function touchLastLogin(int $id): void
    {
        $statement = $this->database->connection()->prepare(
            'UPDATE users SET last_login_at = NOW() WHERE id = :id'
        );

        $statement->execute(['id' => $id]);
    }
}