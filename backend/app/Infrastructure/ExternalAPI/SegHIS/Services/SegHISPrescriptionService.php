<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS\Services;

use App\Infrastructure\ExternalAPI\SegHIS\Client\SegHISHttpClient;

class SegHISPrescriptionService
{
    public function __construct(
        private readonly SegHISHttpClient $client
    ) {
    }

    public function getAll(): mixed
    {
        return $this->client->get(
            '/prescription/show'
        );
    }

    public function getByEncounter(
        string|int $encounterNumber
    ): mixed {
        return $this->client->get(
            '/prescription/viewprescription/encounter_nr/'
            . rawurlencode((string) $encounterNumber)
        );
    }

    public function getByReference(
        string $referenceNumber
    ): mixed {
        return $this->client->get(
            '/prescription/viewprescription/refno/'
            . rawurlencode($referenceNumber)
        );
    }

    public function create(
        array $data
    ): mixed {
        return $this->client->post(
            '/prescription/createprescription',
            $data
        );
    }
}