<?php

declare(strict_types=1);

namespace App\Application\UseCases\Doctors;

use App\Domain\Repositories\SegHIS\DoctorRepositoryInterface;

final class GetDoctorsUseCase
{
    public function __construct(
        private readonly DoctorRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }

    public function find(string $doctorId): array
    {
        return $this->repository->find($doctorId);
    }
}