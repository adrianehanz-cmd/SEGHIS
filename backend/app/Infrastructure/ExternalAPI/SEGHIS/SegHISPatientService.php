<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

final class SegHISPatientService
{
    public function __construct(
        private readonly SegHISClient $client
    ) {
    }

    public function all(): array
    {
        return $this->client->get('patient/show');
    }

    public function find(string $patientId): array
    {
        return $this->client->get(
            'patient/show',
            [
                'pid' => $patientId
            ]
        );
    }

    public function search(string $keyword): array
    {
        return $this->client->get(
            'patient/search',
            [
                'keyword' => $keyword
            ]
        );
    }
}