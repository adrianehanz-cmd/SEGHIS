<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface LaboratoryRepositoryInterface
{
    /**
     * Retrieve all laboratories.
     */
    public function all(): array;

    /**
     * Find laboratory by ID.
     */
    public function find(string $laboratoryId): array;
}