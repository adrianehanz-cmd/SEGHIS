<?php

namespace App\Framework\Controllers;

use App\Shared\Helpers\ApiResponse;

abstract class BaseController
{
    protected function success(
        mixed $data = [],
        string $message = 'Success',
        int $code = 200
    ): void {
        ApiResponse::success($data, $message, $code);
    }

    protected function error(
        string $message,
        int $code = 500
    ): void {
        ApiResponse::error($message, $code);
    }
}