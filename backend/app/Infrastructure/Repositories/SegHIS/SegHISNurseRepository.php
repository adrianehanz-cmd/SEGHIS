<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\SegHIS;

use App\Domain\Repositories\SegHIS\NurseRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISNurseService;

class SegHISNurseRepository
    implements NurseRepositoryInterface
{
    public function __construct(
        private readonly SegHISNurseService $service
    ) {
    }

    public function getAll(): mixed
    {
        return $this->service->getAll();
    }

    public function getById(string|int $id): mixed
    {
        return $this->service->getById($id);
    }

    public function getByDepartment(
        string $departmentId
    ): mixed {
        return $this->service->getByDepartment(
            $departmentId
        );
    }

    public function searchByName(
        string $firstName,
        string $lastName
    ): mixed {
        return $this->service->getByName(
            $firstName,
            $lastName
        );
    }
}