<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\PatientRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISPatientService;

final class SegHISPatientRepository implements PatientRepositoryInterface
{
    public function __construct(
        private readonly SegHISPatientService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }

    public function find(string $patientId): array
    {
        return $this->service->find($patientId);
    }

    public function search(string $keyword): array
    {
        return $this->service->search($keyword);
    }
}