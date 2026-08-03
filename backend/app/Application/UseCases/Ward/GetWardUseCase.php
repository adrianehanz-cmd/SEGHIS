<?php

declare(strict_types=1);

namespace App\Application\UseCases\Ward;

use App\Domain\Repositories\SegHIS\WardRepositoryInterface;

final class GetWardUseCase
{
    public function __construct(
        private readonly WardRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }

    public function find(string $wardId): array
    {
        return $this->repository->find($wardId);
    }
}