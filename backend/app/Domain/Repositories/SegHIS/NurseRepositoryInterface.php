<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface NurseRepositoryInterface
{
    /**
     * Retrieve all nurses.
     */
    public function all(): array;

    /**
     * Find nurse by ID.
     */
    public function find(string $nurseId): array;
}