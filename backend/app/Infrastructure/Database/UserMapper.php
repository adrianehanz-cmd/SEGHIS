<?php

declare(strict_types=1);

namespace App\Infrastructure\Database;

use App\Domain\Entities\User;

final class UserMapper
{
    public static function map(array $row): User
    {
        return new User(
            (int) $row['id'],
            (int) $row['role_id'],
            $row['role_name'],
            $row['username'],
            $row['password_hash'] ?? '',
            $row['first_name'],
            $row['middle_name'] ?? null,
            $row['last_name'],
            $row['email'] ?? null,
            $row['phone'] ?? null,
            (bool) ($row['is_active'] ?? true),
            $row['last_login'] ?? null
        );
    }
}