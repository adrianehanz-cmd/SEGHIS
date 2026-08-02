<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface DoctorRepositoryInterface
{
    /**
     * Retrieve all doctors.
     */
    public function all(): array;

    /**
     * Find doctor by ID.
     */
    public function find(string $doctorId): array;
}