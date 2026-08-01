<?php

declare(strict_types=1);

namespace App\Framework\Middlewares;

use App\Framework\Http\Request;

class SecurityHeadersMiddleware implements MiddlewareInterface
{
    public function handle(
        Request $request,
        callable $next
    ): mixed {
        header('X-Content-Type-Options: nosniff');

        header('X-Frame-Options: DENY');

        header('Referrer-Policy: strict-origin-when-cross-origin');

        header(
            'Permissions-Policy: geolocation=(), microphone=(), camera=()'
        );

        return $next($request);
    }
}