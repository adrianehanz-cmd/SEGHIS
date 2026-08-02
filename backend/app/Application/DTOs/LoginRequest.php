<?php

declare(strict_types=1);

namespace App\Application\DTOs;

final readonly class LoginRequest
{
    public function __construct(
        public string $username,
        public string $password
    ) {
    }
}