<?php

namespace App\Domain\Repositories;

interface RoleRepositoryInterface
{
    /**
     * Find role by ID
     */
    public function findById(int $id): ?array;


    /**
     * Find role by name
     */
    public function findByName(string $name): ?array;


    /**
     * Get all roles
     */
    public function getAll(): array;


    /**
     * Create role
     */
    public function create(array $data): int;


    /**
     * Update role
     */
    public function update(
        int $id,
        array $data
    ): bool;


    /**
     * Delete role
     */
    public function delete(int $id): bool;
}