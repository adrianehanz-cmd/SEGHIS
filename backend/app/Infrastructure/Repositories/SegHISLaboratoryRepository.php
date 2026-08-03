<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\LaboratoryRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISLaboratoryService;

final class SegHISLaboratoryRepository implements LaboratoryRepositoryInterface
{
    public function __construct(
        private readonly SegHISLaboratoryService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }

    public function find(string $laboratoryId): array
    {
        return $this->service->find($laboratoryId);
    }
}