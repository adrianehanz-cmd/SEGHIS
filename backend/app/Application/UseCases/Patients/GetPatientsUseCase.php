<?php

declare(strict_types=1);

namespace App\Application\UseCases\Patients;

use App\Domain\Repositories\SegHIS\PatientRepositoryInterface;

class GetPatientsUseCase
{
    public function __construct(
        private readonly PatientRepositoryInterface $repository
    ) {
    }

    public function execute(): mixed
    {
        return $this->repository->getAll();
    }
}