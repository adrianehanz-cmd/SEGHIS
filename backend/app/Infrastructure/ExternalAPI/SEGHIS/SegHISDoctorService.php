<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

final class SegHISDoctorService
{
    public function __construct(
        private readonly SegHISClient $client
    ) {
    }

    public function all(): array
    {
        return $this->client->get(
            'doctor/show'
        );
    }

    public function find(string $doctorId): array
    {
        return $this->client->get(
            'doctor/show',
            [
                'id' => $doctorId
            ]
        );
    }
}