<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

final class SegHISLaboratoryService
{
    public function __construct(
        private readonly SegHISClient $client
    ) {
    }

    public function all(): array
    {
        return $this->client->get(
            'laboratory/show'
        );
    }

    public function find(string $laboratoryId): array
    {
        return $this->client->get(
            'laboratory/show',
            [
                'id' => $laboratoryId
            ]
        );
    }
}