<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\SegHIS;

use App\Domain\Repositories\SegHIS\RadiologyRepositoryInterface;
use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISRadiologyService;

class SegHISRadiologyRepository
    implements RadiologyRepositoryInterface
{
    public function __construct(
        private readonly SegHISRadiologyService $service
    ) {
    }

    public function getServices(): mixed
    {
        return $this->service->getServices();
    }

    public function getResults(): mixed
    {
        return $this->service->getResults();
    }

    public function getResultsByEncounter(
        string|int $encounterNumber
    ): mixed {
        return $this->service->getResultsByEncounter(
            $encounterNumber
        );
    }

    public function getOrders(): mixed
    {
        return $this->service->getOrders();
    }

    public function getOrdersByEncounter(
        string|int $encounterNumber
    ): mixed {
        return $this->service->getOrdersByEncounter(
            $encounterNumber
        );
    }
}