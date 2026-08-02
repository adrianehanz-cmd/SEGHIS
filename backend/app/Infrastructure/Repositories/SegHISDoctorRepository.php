<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\DoctorRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISDoctorService;

final class SegHISDoctorRepository implements DoctorRepositoryInterface
{
    public function __construct(
        private readonly SegHISDoctorService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }

    public function find(string $doctorId): array
    {
        return $this->service->find($doctorId);
    }
}