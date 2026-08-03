<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

final class SegHISPharmacyService
{
    public function __construct(
        private readonly SegHISClient $client
    ) {
    }

    public function all(): array
    {
        return $this->client->get(
            'pharmacy/show'
        );
    }

    public function find(string $pharmacyId): array
    {
        return $this->client->get(
            'pharmacy/show',
            [
                'id' => $pharmacyId
            ]
        );
    }
}