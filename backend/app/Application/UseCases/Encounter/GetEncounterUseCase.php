<?php

declare(strict_types=1);

namespace App\Application\UseCases\Encounter;

use App\Domain\Repositories\SegHIS\EncounterRepositoryInterface;

final class GetEncounterUseCase
{
    public function __construct(
        private readonly EncounterRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }

    public function find(string $encounterId): array
    {
        return $this->repository->find($encounterId);
    }
}