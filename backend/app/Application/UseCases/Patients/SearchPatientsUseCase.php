<?php

declare(strict_types=1);

namespace App\Application\UseCases\Patients;

use App\Domain\Repositories\SegHIS\PatientRepositoryInterface;

class SearchPatientsUseCase
{
    public function __construct(
        private readonly PatientRepositoryInterface $repository
    ) {
    }

    public function execute(
        string $firstName,
        string $lastName
    ): mixed {
        return $this->repository->searchByName(
            $firstName,
            $lastName
        );
    }
}