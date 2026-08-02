<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use PDO;

final class SessionService
{
    public function __construct(
        private readonly PDO $db
    ) {
    }

    public function create(
        int $userId,
        string $jwtId,
        string $ip,
        string $expiresAt
    ): void {
        $stmt = $this->db->prepare(
            "
            INSERT INTO sessions
            (
                user_id,
                jwt_id,
                ip_address,
                expires_at
            )
            VALUES
            (
                :user_id,
                :jwt_id,
                :ip_address,
                :expires_at
            )
            "
        );

        $stmt->execute([
            'user_id' => $userId,
            'jwt_id' => $jwtId,
            'ip_address' => $ip,
            'expires_at' => $expiresAt,
        ]);
    }
}