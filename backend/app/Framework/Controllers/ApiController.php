<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Shared\Helpers\JsonResponse;
use Throwable;

abstract class ApiController
{
    protected function success(
        mixed $data = null,
        int $status = 200
    ): void {
        JsonResponse::success($data, $status);
    }

    protected function error(
        string $message,
        int $status = 400,
        mixed $errors = null
    ): void {
        JsonResponse::error(
            $message,
            $status,
            $errors
        );
    }

    protected function handleException(
        Throwable $exception
    ): void {
        error_log(
            $exception->getMessage()
        );

        $this->error(
            'An unexpected server error occurred.',
            500
        );
    }
}