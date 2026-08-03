<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\PharmacyRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISPharmacyService;

final class SegHISPharmacyRepository implements PharmacyRepositoryInterface
{
    public function __construct(
        private readonly SegHISPharmacyService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }

    public function find(string $pharmacyId): array
    {
        return $this->service->find($pharmacyId);
    }
}