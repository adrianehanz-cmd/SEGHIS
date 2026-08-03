<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

final class SegHISMiscellaneousService
{
    public function __construct(
        private readonly SegHISClient $client
    ) {
    }

    public function all(): array
    {
        return $this->client->get(
            'miscellaneous/show'
        );
    }

    public function find(string $miscellaneousId): array
    {
        return $this->client->get(
            'miscellaneous/show',
            [
                'id' => $miscellaneousId
            ]
        );
    }
}