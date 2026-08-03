<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\PrescriptionRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISPrescriptionService;

final class SegHISPrescriptionRepository implements PrescriptionRepositoryInterface
{
    public function __construct(
        private readonly SegHISPrescriptionService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }

    public function find(string $prescriptionId): array
    {
        return $this->service->find($prescriptionId);
    }
}