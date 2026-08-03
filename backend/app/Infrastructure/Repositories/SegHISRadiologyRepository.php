<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\RadiologyRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISRadiologyService;

final class SegHISRadiologyRepository implements RadiologyRepositoryInterface
{
    public function __construct(
        private readonly SegHISRadiologyService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }

    public function find(string $radiologyId): array
    {
        return $this->service->find($radiologyId);
    }
}