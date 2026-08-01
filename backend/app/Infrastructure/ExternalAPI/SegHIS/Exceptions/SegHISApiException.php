<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS\Exceptions;

use RuntimeException;

class SegHISApiException extends RuntimeException
{
    public function __construct(
        string $message,
        private readonly int $statusCode = 0,
        private readonly mixed $responseBody = null
    ) {
        parent::__construct($message, $statusCode);
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function responseBody(): mixed
    {
        return $this->responseBody;
    }
}