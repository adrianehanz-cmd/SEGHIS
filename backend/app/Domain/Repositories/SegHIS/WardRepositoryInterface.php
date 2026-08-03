<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface WardRepositoryInterface
{
    /**
     * Retrieve all ward records.
     */
    public function all(): array;

    /**
     * Find ward record by ID.
     */
    public function find(string $wardId): array;
}