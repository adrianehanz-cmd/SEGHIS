<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

final class SegHISWardService
{
    public function __construct(
        private readonly SegHISClient $client
    ) {
    }

    public function all(): array
    {
        return $this->client->get(
            'ward/show'
        );
    }

    public function find(string $wardId): array
    {
        return $this->client->get(
            'ward/show',
            [
                'id' => $wardId
            ]
        );
    }
}