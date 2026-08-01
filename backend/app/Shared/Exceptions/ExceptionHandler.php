<?php

namespace App\Shared\Exceptions;

use App\Infrastructure\Logging\Logger;
use Throwable;

class ExceptionHandler
{
    public static function handle(Throwable $exception): void
    {
        $message = $exception->getMessage();

        if (isset($GLOBALS['container'])) {
            try {
                $logger = $GLOBALS['container']
                    ->get(Logger::class);

                $logger->error()->error(
                    $message,
                    [
                        'exception' => $exception,
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                    ]
                );
            } catch (Throwable) {
                // Logging failure must not mask the original exception.
            }
        }

        http_response_code(500);

        header(
            'Content-Type: application/json; charset=utf-8'
        );

        $response = [
            'success' => false,
            'message' => 'Internal server error.',
        ];

        if (
            ($_ENV['APP_ENV'] ?? 'production') === 'local'
            && ($_ENV['APP_DEBUG'] ?? 'false') === 'true'
        ) {
            $response['debug'] = [
                'message' => $message,
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
            ];
        }

        echo json_encode(
            $response,
            JSON_UNESCAPED_UNICODE
        );
    }
}