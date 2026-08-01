<?php

namespace App\Shared\Helpers;

class ApiResponse
{
    public static function success(
        mixed $data = [],
        string $message = 'Success',
        int $code = 200
    ): void {

        http_response_code($code);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public static function error(
        string $message,
        int $code = 500
    ): void {

        http_response_code($code);

        header('Content-Type: application/json');

        echo json_encode([
            'success' => false,
            'message' => $message
        ]);
    }
}