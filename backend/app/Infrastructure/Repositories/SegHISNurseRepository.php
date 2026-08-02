<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\NurseRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISNurseService;

final class SegHISNurseRepository implements NurseRepositoryInterface
{
    public function __construct(
        private readonly SegHISNurseService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }

    public function find(string $nurseId): array
    {
        return $this->service->find($nurseId);
    }
}