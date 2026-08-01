<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS\Services;

use App\Infrastructure\ExternalAPI\SegHIS\Client\SegHISHttpClient;

class SegHISRadiologyService
{
    public function __construct(
        private readonly SegHISHttpClient $client
    ) {
    }

    public function getServices(): mixed
    {
        return $this->client->get(
            '/radiology/show'
        );
    }

    public function getById(
        string $id
    ): mixed {
        return $this->client->get(
            '/radiology/show/id/'
            . rawurlencode($id)
        );
    }

    public function getByTest(
        string $test
    ): mixed {
        return $this->client->get(
            '/radiology/show/test/'
            . rawurlencode($test)
        );
    }

    public function getBySection(
        string $section
    ): mixed {
        return $this->client->get(
            '/radiology/show/section/'
            . rawurlencode($section)
        );
    }

    public function getResults(): mixed
    {
        return $this->client->get(
            '/radiology/result/'
        );
    }

    public function getResultsByEncounter(
        string|int $encounterNumber
    ): mixed {
        return $this->client->get(
            '/radiology/result/encounter_nr/'
            . rawurlencode((string) $encounterNumber)
        );
    }

    public function getResultsByBatch(
        string|int $batchNumber
    ): mixed {
        return $this->client->get(
            '/radiology/result/batch_nr/'
            . rawurlencode((string) $batchNumber)
        );
    }

    public function getOrders(): mixed
    {
        return $this->client->get(
            '/radiology/vieworder'
        );
    }

    public function getOrdersByEncounter(
        string|int $encounterNumber
    ): mixed {
        return $this->client->get(
            '/radiology/vieworder/encounter_nr/'
            . rawurlencode((string) $encounterNumber)
        );
    }

    public function getOrdersByReference(
        string|int $referenceNumber
    ): mixed {
        return $this->client->get(
            '/radiology/vieworder/refno/'
            . rawurlencode((string) $referenceNumber)
        );
    }

    public function createOrder(
        array $data
    ): mixed {
        return $this->client->post(
            '/radiology/createorder',
            $data
        );
    }

    public function updateOrder(
        string|int $referenceNumber,
        array $data
    ): mixed {
        return $this->client->put(
            '/radiology/updateorder/id/'
            . rawurlencode((string) $referenceNumber),
            $data
        );
    }
}