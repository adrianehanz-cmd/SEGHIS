<?php

declare(strict_types=1);

namespace App\Application\UseCases\Patients;

use App\Domain\Repositories\SegHIS\PatientRepositoryInterface;

final class GetPatientsUseCase
{
    public function __construct(
        private readonly PatientRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }

    public function find(string $patientId): array
    {
        return $this->repository->find($patientId);
    }

    public function search(string $keyword): array
    {
        return $this->repository->search($keyword);
    }
}