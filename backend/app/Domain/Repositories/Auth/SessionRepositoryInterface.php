<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Auth;

interface SessionRepositoryInterface
{
    public function create(
        int $userId,
        string $jti,
        string $expiresAt,
        ?string $ip,
        ?string $userAgent
    ): void;

    public function findByJti(string $jti): ?array;

    public function revokeByJti(string $jti): void;

    public function revokeAllForUser(int $userId): void;
}