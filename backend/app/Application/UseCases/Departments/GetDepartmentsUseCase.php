<?php

declare(strict_types=1);

namespace App\Application\UseCases\Departments;

use App\Domain\Repositories\SegHIS\DepartmentRepositoryInterface;

class GetDepartmentsUseCase
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $repository
    ) {
    }

    public function execute(): mixed
    {
        return $this->repository->getAll();
    }
}