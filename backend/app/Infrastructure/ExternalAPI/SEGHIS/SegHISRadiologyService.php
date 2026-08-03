<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

final class SegHISRadiologyService
{
    public function __construct(
        private readonly SegHISClient $client
    ) {
    }

    public function all(): array
    {
        return $this->client->get(
            'radiology/show'
        );
    }

    public function find(string $radiologyId): array
    {
        return $this->client->get(
            'radiology/show',
            [
                'id' => $radiologyId
            ]
        );
    }
}