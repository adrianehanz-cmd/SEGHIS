<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Auth\LoginUseCase;
use App\Application\UseCases\Auth\LogoutUseCase;
use App\Application\UseCases\Auth\RefreshTokenUseCase;
use App\Infrastructure\Security\JWTManager;
use Throwable;

final class AuthController extends ApiController
{
    public function __construct(
        private readonly LoginUseCase $login,
        private readonly RefreshTokenUseCase $refreshToken,
        private readonly LogoutUseCase $logout,
        private readonly JWTManager $jwtManager
    ) {
    }

    public function login(): void
    {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $username = trim($body['username'] ?? '');
        $password = (string) ($body['password'] ?? '');

        if ($username === '' || $password === '') {
            $this->error('Username and password are required.', 422);

            return;
        }

        try {
            $result = $this->login->execute($username, $password);

            $this->success($result);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), $exception->getCode() ?: 401);
        }
    }

    public function refresh(): void
    {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $refreshToken = $body['refresh_token'] ?? '';

        if ($refreshToken === '') {
            $this->error('Refresh token is required.', 422);

            return;
        }

        try {
            $result = $this->refreshToken->execute($refreshToken);

            $this->success($result);
        } catch (Throwable $exception) {
            $this->error($exception->getMessage(), $exception->getCode() ?: 401);
        }
    }

    public function logout(): void
    {
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $refreshToken = $body['refresh_token'] ?? '';
        $auth = $GLOBALS['auth_user'] ?? null;

        if ($refreshToken === '' || !$auth) {
            $this->error('Refresh token and authentication are required.', 422);

            return;
        }

        try {
            $claims = $this->jwtManager->verify($refreshToken);

            $this->logout->execute($claims->jti, (int) $auth->sub);

            $this->success(null, 'Logged out.');
        } catch (Throwable) {
            $this->error('Unable to log out.', 400);
        }
    }

    public function me(): void
    {
        $auth = $GLOBALS['auth_user'] ?? null;

        if (!$auth) {
            $this->error('Not authenticated.', 401);

            return;
        }

        $this->success([
            'id' => $auth->sub,
            'username' => $auth->username,
            'role' => $auth->role,
        ]);
    }
}