<?php

declare(strict_types=1);

namespace App\Application\UseCases\Departments;

use App\Domain\Repositories\SegHIS\DepartmentRepositoryInterface;

final class GetDepartmentsUseCase
{
    public function __construct(
        private readonly DepartmentRepositoryInterface $repository
    ) {
    }

    public function execute(): array
    {
        return $this->repository->all();
    }
}