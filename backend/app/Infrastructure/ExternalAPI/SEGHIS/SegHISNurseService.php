<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

final class SegHISNurseService
{
    public function __construct(
        private readonly SegHISClient $client
    ) {
    }

    public function all(): array
    {
        return $this->client->get(
            'nurse/show'
        );
    }

    public function find(string $nurseId): array
    {
        return $this->client->get(
            'nurse/show',
            [
                'id' => $nurseId
            ]
        );
    }
}