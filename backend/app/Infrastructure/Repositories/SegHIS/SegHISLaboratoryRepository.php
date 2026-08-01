<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\SegHIS;

use App\Domain\Repositories\SegHIS\LaboratoryRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISLaboratoryService;

class SegHISLaboratoryRepository
    implements LaboratoryRepositoryInterface
{
    public function __construct(
        private readonly SegHISLaboratoryService $service
    ) {
    }

    public function getAllResults(): mixed
    {
        return $this->service->getAllResults();
    }

    public function getByPatient(
        string $lastName,
        string $firstName
    ): mixed {
        return $this->service->getResultsByPatient(
            $lastName,
            $firstName
        );
    }

    public function getByHrn(
        string|int $hrn
    ): mixed {
        return $this->service->getResultsByHrn(
            $hrn
        );
    }
}