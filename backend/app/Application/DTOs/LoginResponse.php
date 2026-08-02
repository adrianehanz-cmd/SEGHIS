<?php

declare(strict_types=1);

namespace App\Application\DTOs;

final readonly class LoginResponse
{
    public function __construct(
        public string $token,
        public int $expiresIn,
        public array $user
    ) {
    }
}