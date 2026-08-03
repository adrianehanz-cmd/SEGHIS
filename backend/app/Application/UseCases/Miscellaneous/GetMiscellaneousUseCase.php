<?php

declare(strict_types=1);

namespace App\Application\UseCases\Miscellaneous;

use App\Domain\Repositories\SegHIS\MiscellaneousRepositoryInterface;

final class GetMiscellaneousUseCase
{
    public function __construct(
        private readonly MiscellaneousRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }

    public function find(string $miscellaneousId): array
    {
        return $this->repository->find($miscellaneousId);
    }
}