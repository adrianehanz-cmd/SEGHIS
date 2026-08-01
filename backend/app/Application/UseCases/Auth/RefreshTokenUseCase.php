<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Domain\Repositories\Auth\SessionRepositoryInterface;
use App\Domain\Repositories\Auth\UserRepositoryInterface;
use App\Infrastructure\Security\JWTManager;
use RuntimeException;
use Throwable;

class RefreshTokenUseCase
{
    public function __construct(
        private readonly SessionRepositoryInterface $sessions,
        private readonly UserRepositoryInterface $users,
        private readonly JWTManager $jwtManager
    ) {
    }

    public function execute(string $refreshToken): array
    {
        try {
            $claims = $this->jwtManager->verify($refreshToken);
        } catch (Throwable) {
            throw new RuntimeException('Invalid or expired refresh token.', 401);
        }

        if (($claims->type ?? null) !== 'refresh') {
            throw new RuntimeException('Invalid token type.', 401);
        }

        $session = $this->sessions->findByJti($claims->jti);

        if (!$session || $session['revoked_at'] !== null) {
            throw new RuntimeException('Session has been revoked.', 401);
        }

        $user = $this->users->findById((int) $claims->sub);

        if (!$user || !$user['is_active']) {
            throw new RuntimeException('User no longer active.', 401);
        }

        // Rotate refresh token
        $this->sessions->revokeByJti($claims->jti);

        $accessClaims = [
            'sub' => (int) $user['id'],
            'username' => $user['username'],
            'role' => $user['role_name'],
        ];

        $access = $this->jwtManager->generateAccessToken($accessClaims);
        $refresh = $this->jwtManager->generateRefreshToken(['sub' => (int) $user['id']]);

        $this->sessions->create(
            (int) $user['id'],
            $refresh['jti'],
            date('Y-m-d H:i:s', $refresh['expires_at']),
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        );

        return [
            'access_token' => $access['token'],
            'refresh_token' => $refresh['token'],
            'expires_in' => $this->jwtManager->accessTtl(),
        ];
    }
}