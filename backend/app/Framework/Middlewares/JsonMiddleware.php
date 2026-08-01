<?php

namespace App\Framework\Middlewares;

use App\Framework\Http\Request;
use App\Framework\Http\Response;

class JsonMiddleware implements MiddlewareInterface
{
    public function handle(
        Request $request,
        callable $next
    ): mixed {
        $contentType = $request->header('Content-Type');

        if (
            in_array(
                $request->method(),
                ['POST', 'PUT', 'PATCH'],
                true
            )
            && $contentType
            && str_contains(
                strtolower($contentType),
                'application/json'
            )
        ) {
            json_decode(
                file_get_contents('php://input'),
                true
            );

            if (json_last_error() !== JSON_ERROR_NONE) {
                Response::json(
                    null,
                    'Invalid JSON request body.',
                    400
                );
            }
        }

        return $next($request);
    }
}