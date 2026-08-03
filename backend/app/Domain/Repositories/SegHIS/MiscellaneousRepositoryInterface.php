<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface MiscellaneousRepositoryInterface
{
    /**
     * Retrieve all miscellaneous items.
     */
    public function all(): array;

    /**
     * Find miscellaneous item by ID.
     */
    public function find(string $miscellaneousId): array;
}