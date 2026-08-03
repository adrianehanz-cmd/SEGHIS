<?php

declare(strict_types=1);

namespace App\Application\UseCases\Laboratory;

use App\Domain\Repositories\SegHIS\LaboratoryRepositoryInterface;

final class GetLaboratoryUseCase
{
    public function __construct(
        private readonly LaboratoryRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }

    public function find(string $laboratoryId): array
    {
        return $this->repository->find($laboratoryId);
    }
}