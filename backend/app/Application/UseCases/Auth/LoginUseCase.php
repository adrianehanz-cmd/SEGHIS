<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Domain\Repositories\Auth\SessionRepositoryInterface;
use App\Domain\Repositories\Auth\UserRepositoryInterface;
use App\Infrastructure\Logging\AuditLogger;
use App\Infrastructure\Security\JWTManager;
use RuntimeException;

class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly SessionRepositoryInterface $sessions,
        private readonly JWTManager $jwtManager,
        private readonly AuditLogger $auditLogger
    ) {
    }

    public function execute(string $username, string $password): array
    {
        $user = $this->users->findByUsername($username);

        if (!$user || !$user['is_active']) {
            $this->auditLogger->log(null, 'login_failed', 'user', $username);

            throw new RuntimeException('Invalid credentials.', 401);
        }

        if (!password_verify($password, $user['password_hash'])) {
            $this->auditLogger->log((int) $user['id'], 'login_failed', 'user', $user['id']);

            throw new RuntimeException('Invalid credentials.', 401);
        }

        $claims = [
            'sub' => (int) $user['id'],
            'username' => $user['username'],
            'role' => $user['role_name'],
        ];

        $access = $this->jwtManager->generateAccessToken($claims);
        $refresh = $this->jwtManager->generateRefreshToken(['sub' => (int) $user['id']]);

        $this->sessions->create(
            (int) $user['id'],
            $refresh['jti'],
            date('Y-m-d H:i:s', $refresh['expires_at']),
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        );

        $this->users->touchLastLogin((int) $user['id']);
        $this->auditLogger->log((int) $user['id'], 'login_success', 'user', $user['id']);

        return [
            'access_token' => $access['token'],
            'refresh_token' => $refresh['token'],
            'expires_in' => $this->jwtManager->accessTtl(),
            'user' => [
                'id' => (int) $user['id'],
                'username' => $user['username'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'role' => $user['role_name'],
            ],
        ];
    }
}