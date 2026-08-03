<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

final class SegHISEncounterService
{
    public function __construct(
        private readonly SegHISClient $client
    ) {
    }

    public function all(): array
    {
        return $this->client->get(
            'encounter/show'
        );
    }

    public function find(string $encounterId): array
    {
        return $this->client->get(
            'encounter/show',
            [
                'id' => $encounterId
            ]
        );
    }
}