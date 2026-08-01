<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\SegHIS;

use App\Domain\Repositories\SegHIS\DoctorRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISDoctorService;

class SegHISDoctorRepository
    implements DoctorRepositoryInterface
{
    public function __construct(
        private readonly SegHISDoctorService $service
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

    public function getByDepartment(
        string $departmentId
    ): mixed {
        return $this->service->getByDepartment(
            $departmentId
        );
    }

    public function searchByName(
        string $firstName,
        string $lastName
    ): mixed {
        return $this->service->getByName(
            $firstName,
            $lastName
        );
    }
}