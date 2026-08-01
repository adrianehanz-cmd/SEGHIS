<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Domain\Repositories\Auth\SessionRepositoryInterface;
use App\Infrastructure\Logging\AuditLogger;

class LogoutUseCase
{
    public function __construct(
        private readonly SessionRepositoryInterface $sessions,
        private readonly AuditLogger $auditLogger
    ) {
    }

    public function execute(string $refreshTokenJti, int $userId): void
    {
        $this->sessions->revokeByJti($refreshTokenJti);
        $this->auditLogger->log($userId, 'logout', 'user', $userId);
    }
}