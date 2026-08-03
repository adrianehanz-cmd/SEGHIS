<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface PharmacyRepositoryInterface
{
    /**
     * Retrieve all pharmacies.
     */
    public function all(): array;

    /**
     * Find pharmacy by ID.
     */
    public function find(string $pharmacyId): array;
}