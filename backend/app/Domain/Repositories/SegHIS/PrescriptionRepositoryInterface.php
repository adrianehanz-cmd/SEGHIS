<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface PrescriptionRepositoryInterface
{
    /**
     * Retrieve all prescriptions.
     */
    public function all(): array;

    /**
     * Find prescription by ID.
     */
    public function find(string $prescriptionId): array;
}