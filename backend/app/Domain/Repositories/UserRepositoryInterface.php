<?php

declare(strict_types=1);

namespace App\Domain\Repositories;

use App\Domain\Entities\User;

interface UserRepositoryInterface
{
    public function findByUsername(string $username): ?User;

    public function findById(int $id): ?User;

    public function updateLastLogin(int $userId): void;
}