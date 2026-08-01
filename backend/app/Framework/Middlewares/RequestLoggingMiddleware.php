<?php

declare(strict_types=1);

namespace App\Framework\Middlewares;

use App\Framework\Http\Request;
use App\Infrastructure\Logging\Logger;
use Ramsey\Uuid\Uuid;

class RequestLoggingMiddleware implements MiddlewareInterface
{
    public function __construct(
        private Logger $logger
    ) {
    }

    public function handle(
        Request $request,
        callable $next
    ): mixed {
        $requestId = Uuid::uuid4()->toString();

        header('X-Request-ID: ' . $requestId);

        $start = microtime(true);

        $this->logger->api()->info(
            'Incoming request',
            [
                'request_id' => $requestId,
                'method' => $request->method(),
                'uri' => $request->uri(),
                'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            ]
        );

        try {
            return $next($request);
        } finally {
            $duration = microtime(true) - $start;

            $this->logger->api()->info(
                'Request completed',
                [
                    'request_id' => $requestId,
                    'duration_ms' => round(
                        $duration * 1000,
                        2
                    ),
                ]
            );
        }
    }
}