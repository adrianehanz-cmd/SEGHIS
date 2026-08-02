<?php

declare(strict_types=1);

namespace App\Application\UseCases\Auth;

use App\Application\DTOs\LoginRequest;
use App\Application\DTOs\LoginResponse;
use App\Domain\Repositories\UserRepositoryInterface;
use App\Infrastructure\Security\JwtService;
use App\Infrastructure\Security\PasswordService;
use App\Infrastructure\Security\SessionService;
use App\Shared\Exceptions\AuthenticationException;

final class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $users,
        private readonly PasswordService $passwordService,
        private readonly JwtService $jwtService,
        private readonly SessionService $sessionService
    ) {
    }

    public function execute(
        LoginRequest $request
    ): LoginResponse {

        $user = $this->users->findByUsername(
            $request->username
        );

        if ($user === null) {
            throw new AuthenticationException(
                'Invalid username or password.'
            );
        }

        if (!$user->isActive()) {
            throw new AuthenticationException(
                'User account is inactive.'
            );
        }

        if (
            !$this->passwordService->verify(
                $request->password,
                $user->getPassword()
            )
        ) {
            throw new AuthenticationException(
                'Invalid username or password.'
            );
        }

        $jti = bin2hex(random_bytes(16));

        $token = $this->jwtService->generate([
            'sub' => $user->getId(),
            'username' => $user->getUsername(),
            'role_id' => $user->getRoleId(),
            'jti' => $jti,
        ]);

        $expires = date(
            'Y-m-d H:i:s',
            time() + 3600
        );

        $this->sessionService->create(
            $user->getId(),
            $jti,
            $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
            $expires
        );

        $this->users->updateLastLogin(
            $user->getId()
        );

        return new LoginResponse(
            token: $token,
            expiresIn: 3600,
            user: [
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'name' => $user->getFullName(),
                'role_id' => $user->getRoleId(),
            ]
        );
    }
}