<?php

declare(strict_types=1);

namespace App\Application\UseCases\Radiology;

use App\Domain\Repositories\SegHIS\RadiologyRepositoryInterface;

final class GetRadiologyUseCase
{
    public function __construct(
        private readonly RadiologyRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }

    public function find(string $radiologyId): array
    {
        return $this->repository->find($radiologyId);
    }
}