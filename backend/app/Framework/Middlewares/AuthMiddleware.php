<?php

namespace App\Framework\Middlewares;

use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Infrastructure\Security\JWTManager;
use Throwable;

class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(
        private JWTManager $jwtManager
    ) {
    }

    public function handle(
        Request $request,
        callable $next
    ): mixed {
        $token = $request->bearerToken();

        if (!$token) {
            Response::json(null, 'Authentication required.', 401);
        }

        try {
            $claims = $this->jwtManager->verify($token);

            if (($claims->type ?? null) !== 'access') {
                Response::json(null, 'Invalid token type.', 401);
            }

            $GLOBALS['auth_user'] = $claims;

            return $next($request);
        } catch (Throwable) {
            Response::json(null, 'Invalid or expired authentication token.', 401);
        }
    }
}