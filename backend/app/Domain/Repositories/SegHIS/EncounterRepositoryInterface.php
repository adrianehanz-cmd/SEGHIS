<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface EncounterRepositoryInterface
{
    public function getAll(): mixed;

    public function getById(string|int $id): mixed;

    public function getByPatientId(
        string|int $patientId
    ): mixed;

    public function getByDepartment(
        string $departmentId
    ): mixed;

    public function getByDoctor(
        string|int $doctorNumber
    ): mixed;

    public function getByDate(
        string $date
    ): mixed;
}