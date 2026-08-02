<?php

declare(strict_types=1);

namespace App\Framework\Middlewares;

use App\Domain\Repositories\UserRepositoryInterface;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Infrastructure\Security\JwtService;

final class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly JwtService $jwt,
        private readonly UserRepositoryInterface $users
    ) {
    }

    public function handle(
        Request $request,
        callable $next
    ): mixed {

        $token = $request->bearerToken();

        if (!$token) {
            Response::json(
                null,
                'Authentication token missing.',
                401
            );
        }

        if (!$this->jwt->validate($token)) {
            Response::json(
                null,
                'Invalid or expired token.',
                401
            );
        }

        $payload = $this->jwt->payload($token);

        $user = $this->users->findById(
            (int) $payload->sub
        );

        if (!$user) {
            Response::json(
                null,
                'User not found.',
                401
            );
        }

        $request->setUser([
            'id'       => $user->getId(),
            'username' => $user->getUsername(),
            'role_id'  => $user->getRoleId(),
            'role'     => strtolower($user->getRoleName()),
            'name'     => $user->getFullName(),
        ]);

        return $next($request);
    }
}