<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\Auth;

use App\Domain\Repositories\Auth\SessionRepositoryInterface;
use App\Infrastructure\Database\Database;

class SessionRepository implements SessionRepositoryInterface
{
    public function __construct(
        private readonly Database $database
    ) {
    }

    public function create(
        int $userId,
        string $jti,
        string $expiresAt,
        ?string $ip,
        ?string $userAgent
    ): void {
        $statement = $this->database->connection()->prepare(
            'INSERT INTO sessions (user_id, token_jti, ip_address, user_agent, expires_at)
             VALUES (:user_id, :jti, :ip, :agent, :expires_at)'
        );

        $statement->execute([
            'user_id' => $userId,
            'jti' => $jti,
            'ip' => $ip,
            'agent' => $userAgent,
            'expires_at' => $expiresAt,
        ]);
    }

    public function findByJti(string $jti): ?array
    {
        $statement = $this->database->connection()->prepare(
            'SELECT * FROM sessions WHERE token_jti = :jti LIMIT 1'
        );

        $statement->execute(['jti' => $jti]);

        $session = $statement->fetch();

        return $session ?: null;
    }

    public function revokeByJti(string $jti): void
    {
        $statement = $this->database->connection()->prepare(
            'UPDATE sessions SET revoked_at = NOW() WHERE token_jti = :jti'
        );

        $statement->execute(['jti' => $jti]);
    }

    public function revokeAllForUser(int $userId): void
    {
        $statement = $this->database->connection()->prepare(
            'UPDATE sessions SET revoked_at = NOW()
             WHERE user_id = :user_id AND revoked_at IS NULL'
        );

        $statement->execute(['user_id' => $userId]);
    }
}