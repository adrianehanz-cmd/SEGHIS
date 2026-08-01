<?php

declare(strict_types=1);

namespace App\Application\UseCases\Patients;

use App\Domain\Repositories\SegHIS\PatientRepositoryInterface;

class GetPatientUseCase
{
    public function __construct(
        private readonly PatientRepositoryInterface $repository
    ) {
    }

    public function execute(
        string|int $id
    ): mixed {
        return $this->repository->getById($id);
    }
}