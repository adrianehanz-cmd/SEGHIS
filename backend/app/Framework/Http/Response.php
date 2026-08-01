<?php

namespace App\Framework\Http;

class Response
{
    public static function json(
        mixed $data = null,
        string $message = 'Success',
        int $status = 200
    ): never {
        http_response_code($status);

        header('Content-Type: application/json; charset=utf-8');

        echo json_encode(
            [
                'success' => $status >= 200 && $status < 300,
                'message' => $message,
                'data' => $data,
            ],
            JSON_UNESCAPED_UNICODE
        );

        exit;
    }
}