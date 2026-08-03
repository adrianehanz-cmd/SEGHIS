<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\WardRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISWardService;

final class SegHISWardRepository implements WardRepositoryInterface
{
    public function __construct(
        private readonly SegHISWardService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }

    public function find(string $wardId): array
    {
        return $this->service->find($wardId);
    }
}