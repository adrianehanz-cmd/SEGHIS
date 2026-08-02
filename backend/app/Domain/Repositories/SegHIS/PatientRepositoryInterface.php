<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface PatientRepositoryInterface
{
    /**
     * Get all patients.
     */
    public function all(): array;

    /**
     * Find a patient by ID.
     */
    public function find(string $patientId): array;

    /**
     * Search patients.
     */
    public function search(string $keyword): array;
}