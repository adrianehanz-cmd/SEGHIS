<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface RadiologyRepositoryInterface
{
    public function getServices(): mixed;

    public function getResults(): mixed;

    public function getResultsByEncounter(
        string|int $encounterNumber
    ): mixed;

    public function getOrders(): mixed;

    public function getOrdersByEncounter(
        string|int $encounterNumber
    ): mixed;
}