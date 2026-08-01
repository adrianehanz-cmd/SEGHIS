<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\SegHIS;

use App\Domain\Repositories\SegHIS\DepartmentRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISDepartmentService;

class SegHISDepartmentRepository
    implements DepartmentRepositoryInterface
{
    public function __construct(
        private readonly SegHISDepartmentService $service
    ) {
    }

    public function getAll(): mixed
    {
        return $this->service->getAll();
    }
}