<?php

declare(strict_types=1);

namespace App\Framework\Middlewares;

use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class RoleMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly array $roles
    ) {
    }

    public function handle(
        Request $request,
        callable $next
    ): mixed {

        $user = $request->user();

        if ($user === null) {
            Response::json(
                null,
                'Unauthorized',
                401
            );
        }

        $role = strtolower((string) ($user['role'] ?? ''));

        if (
            $role === '' ||
            !in_array($role, $this->roles, true)
        ) {
            Response::json(
                null,
                'Forbidden',
                403
            );
        }

        return $next($request);
    }
}