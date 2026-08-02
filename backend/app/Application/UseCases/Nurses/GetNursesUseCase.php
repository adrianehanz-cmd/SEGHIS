<?php

declare(strict_types=1);

namespace App\Application\UseCases\Nurses;

use App\Domain\Repositories\SegHIS\NurseRepositoryInterface;

final class GetNursesUseCase
{
    public function __construct(
        private readonly NurseRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }

    public function find(string $nurseId): array
    {
        return $this->repository->find($nurseId);
    }
}