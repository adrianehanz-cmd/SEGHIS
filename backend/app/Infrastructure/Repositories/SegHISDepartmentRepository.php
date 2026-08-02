<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\DepartmentRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISDepartmentService;

final class SegHISDepartmentRepository implements DepartmentRepositoryInterface
{
    public function __construct(
        private readonly SegHISDepartmentService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }
}