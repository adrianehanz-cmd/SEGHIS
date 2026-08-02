<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\DTOs\LoginRequest;
use App\Application\UseCases\Auth\LoginUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Shared\Exceptions\AuthenticationException;
use Throwable;

final class AuthController
{
    public function __construct(
        private readonly LoginUseCase $loginUseCase
    ) {
    }

    public function login(Request $request): void
    {
        try {
            $dto = new LoginRequest(
            username: trim($request->input('username')),
            password: $request->input('password')
        );

            $result = $this->loginUseCase->execute($dto);

            Response::json(
                [
                    'token' => $result->token,
                    'expires_in' => $result->expiresIn,
                    'user' => $result->user,
                ],
                'Login successful.'
            );
        } catch (AuthenticationException $e) {
            Response::json(null, $e->getMessage(), 401);
        } catch (Throwable $e) {
            Response::json(null, $e->getMessage(), 500);
        }
    }

    public function me(Request $request): void
{
    Response::json(
        $request->user(),
        'Authenticated user.'
    );
}

    public function logout(): void
    {
        Response::json(
            null,
            'Logout successful.'
        );
    }

    public function refresh(): void
    {
        Response::json(
            null,
            'Token refreshed.'
        );
    }
}