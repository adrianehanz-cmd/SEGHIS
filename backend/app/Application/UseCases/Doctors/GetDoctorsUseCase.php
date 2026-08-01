<?php

declare(strict_types=1);

namespace App\Application\UseCases\Doctors;

use App\Domain\Repositories\SegHIS\DoctorRepositoryInterface;

class GetDoctorsUseCase
{
    public function __construct(
        private readonly DoctorRepositoryInterface $repository
    ) {
    }

    public function execute(): mixed
    {
        return $this->repository->getAll();
    }
}