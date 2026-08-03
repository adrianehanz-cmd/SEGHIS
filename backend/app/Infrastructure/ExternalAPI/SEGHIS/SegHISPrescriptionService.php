<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

final class SegHISPrescriptionService
{
    public function __construct(
        private readonly SegHISClient $client
    ) {
    }

    public function all(): array
    {
        return $this->client->get(
            'prescription/show'
        );
    }

    public function find(string $prescriptionId): array
    {
        return $this->client->get(
            'prescription/show',
            [
                'id' => $prescriptionId
            ]
        );
    }
}