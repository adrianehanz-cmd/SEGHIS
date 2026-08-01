<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\SegHIS;

use App\Domain\Repositories\SegHIS\EncounterRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISEncounterService;

class SegHISEncounterRepository
    implements EncounterRepositoryInterface
{
    public function __construct(
        private readonly SegHISEncounterService $service
    ) {
    }

    public function getAll(): mixed
    {
        return $this->service->getAll();
    }

    public function getById(string|int $id): mixed
    {
        return $this->service->getById($id);
    }

    public function getByPatientId(
        string|int $patientId
    ): mixed {
        return $this->service->getByPatientId(
            $patientId
        );
    }

    public function getByDepartment(
        string $departmentId
    ): mixed {
        return $this->service->getByDepartment(
            $departmentId
        );
    }

    public function getByDoctor(
        string|int $doctorNumber
    ): mixed {
        return $this->service->getByDoctor(
            $doctorNumber
        );
    }

    public function getByDate(
        string $date
    ): mixed {
        return $this->service->getByEncounterDate(
            $date
        );
    }
}