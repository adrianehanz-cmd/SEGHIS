<?php

declare(strict_types=1);

namespace App\Application\UseCases\Pharmacy;

use App\Domain\Repositories\SegHIS\PharmacyRepositoryInterface;

final class GetPharmacyUseCase
{
    public function __construct(
        private readonly PharmacyRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }

    public function find(string $pharmacyId): array
    {
        return $this->repository->find($pharmacyId);
    }
}