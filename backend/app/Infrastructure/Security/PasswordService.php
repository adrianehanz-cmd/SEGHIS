<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

final class PasswordService
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public function verify(
        string $plainPassword,
        string $hashedPassword
    ): bool {
        return password_verify(
            $plainPassword,
            $hashedPassword
        );
    }
}