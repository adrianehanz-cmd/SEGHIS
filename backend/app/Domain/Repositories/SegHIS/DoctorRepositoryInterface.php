<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface DoctorRepositoryInterface
{
    public function getAll(): mixed;

    public function getById(string|int $id): mixed;

    public function getByDepartment(
        string $departmentId
    ): mixed;

    public function searchByName(
        string $firstName,
        string $lastName
    ): mixed;
}