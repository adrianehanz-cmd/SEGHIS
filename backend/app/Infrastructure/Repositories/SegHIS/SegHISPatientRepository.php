<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\SegHIS;

use App\Domain\Repositories\SegHIS\PatientRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISPatientService;

class SegHISPatientRepository implements PatientRepositoryInterface
{
    public function __construct(
        private readonly SegHISPatientService $service
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