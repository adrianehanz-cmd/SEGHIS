<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories;

use App\Domain\Repositories\SegHIS\MiscellaneousRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\SegHISMiscellaneousService;

final class SegHISMiscellaneousRepository implements MiscellaneousRepositoryInterface
{
    public function __construct(
        private readonly SegHISMiscellaneousService $service
    ) {
    }

    public function all(): array
    {
        return $this->service->all();
    }

    public function find(string $miscellaneousId): array
    {
        return $this->service->find($miscellaneousId);
    }
}