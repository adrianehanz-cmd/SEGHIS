<?php

declare(strict_types=1);

namespace App\Framework\Middlewares;

use App\Framework\Http\Request;
use App\Framework\Http\Response;

class RoleMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly array $allowedRoles
    ) {
    }

    public function handle(
        Request $request,
        callable $next
    ): mixed {
        $auth = $GLOBALS['auth_user'] ?? null;

        if (!$auth || !in_array($auth->role ?? null, $this->allowedRoles, true)) {
            Response::json(null, 'Insufficient permissions.', 403);
        }

        return $next($request);
    }
}