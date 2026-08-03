<?php

declare(strict_types=1);

namespace App\Application\UseCases\Prescription;

use App\Domain\Repositories\SegHIS\PrescriptionRepositoryInterface;

final class GetPrescriptionUseCase
{
    public function __construct(
        private readonly PrescriptionRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }

    public function find(string $prescriptionId): array
    {
        return $this->repository->find($prescriptionId);
    }
}