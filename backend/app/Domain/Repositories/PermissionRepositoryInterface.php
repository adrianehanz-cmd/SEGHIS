<?php

declare(strict_types=1);

namespace App\Domain\Repositories;


interface PermissionRepositoryInterface
{

    public function userHasPermission(
        int $userId,
        string $permission
    ): bool;


}