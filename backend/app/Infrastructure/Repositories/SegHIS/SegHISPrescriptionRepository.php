<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\SegHIS;

use App\Domain\Repositories\SegHIS\PrescriptionRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISPrescriptionService;

class SegHISPrescriptionRepository
    implements PrescriptionRepositoryInterface
{
    public function __construct(
        private readonly SegHISPrescriptionService $service
    ) {
    }

    public function getAll(): mixed
    {
        return $this->service->getAll();
    }

    public function getByEncounter(
        string|int $encounterNumber
    ): mixed {
        return $this->service->getByEncounter(
            $encounterNumber
        );
    }

    public function getByReference(
        string $referenceNumber
    ): mixed {
        return $this->service->getByReference(
            $referenceNumber
        );
    }
}