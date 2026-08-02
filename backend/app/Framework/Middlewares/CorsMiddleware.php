<?php

declare(strict_types=1);

namespace App\Framework\Middlewares;

use App\Framework\Http\Request;

final class CorsMiddleware implements MiddlewareInterface
{
    public function handle(
        Request $request,
        callable $next
    ): mixed {

        header('Access-Control-Allow-Origin: http://localhost:5173');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        header('Access-Control-Allow-Credentials: true');

        if ($request->method() === 'OPTIONS') {
            http_response_code(204);
            exit;
        }

        return $next($request);
    }
}