<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\EncounterRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISEncounterService;

final class SegHISEncounterRepository implements EncounterRepositoryInterface
{
    public function __construct(
        private readonly SegHISEncounterService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }

    public function find(string $encounterId): array
    {
        return $this->service->find($encounterId);
    }
}