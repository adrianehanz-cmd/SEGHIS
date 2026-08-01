<?php

declare(strict_types=1);

namespace App\Shared\Helpers;

final class JsonResponse
{
    public static function success(
        mixed $data = null,
        int $status = 200
    ): void {
        self::send(
            [
                'success' => true,
                'data' => $data,
            ],
            $status
        );
    }

    public static function error(
        string $message,
        int $status = 400,
        mixed $errors = null
    ): void {
        $response = [
            'success' => false,
            'message' => $message,
        ];

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        self::send($response, $status);
    }

    private static function send(
        array $response,
        int $status
    ): void {
        http_response_code($status);

        header('Content-Type: application/json');
        header('Cache-Control: no-store');

        echo json_encode(
            $response,
            JSON_UNESCAPED_UNICODE |
            JSON_UNESCAPED_SLASHES
        );
    }
}