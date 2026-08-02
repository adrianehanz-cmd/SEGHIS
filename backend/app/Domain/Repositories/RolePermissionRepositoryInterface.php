<?php

namespace App\Domain\Repositories;

interface RolePermissionRepositoryInterface
{
    /**
     * Assign permission to role
     */
    public function assign(
        int $roleId,
        int $permissionId
    ): bool;


    /**
     * Remove permission from role
     */
    public function remove(
        int $roleId,
        int $permissionId
    ): bool;


    /**
     * Get permissions of a role
     */
    public function getPermissionsByRole(
        int $roleId
    ): array;


    /**
     * Check if role has permission
     */
    public function hasPermission(
        int $roleId,
        string $permission
    ): bool;
}