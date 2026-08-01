<?php

declare(strict_types=1);

namespace App\Domain\Repositories\Auth;

interface UserRepositoryInterface
{
    public function findByUsername(string $username): ?array;

    public function findById(int $id): ?array;

    public function touchLastLogin(int $id): void;
}