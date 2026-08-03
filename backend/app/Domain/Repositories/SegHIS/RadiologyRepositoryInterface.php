<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface RadiologyRepositoryInterface
{
    /**
     * Retrieve all radiology records.
     */
    public function all(): array;

    /**
     * Find radiology record by ID.
     */
    public function find(string $radiologyId): array;
}