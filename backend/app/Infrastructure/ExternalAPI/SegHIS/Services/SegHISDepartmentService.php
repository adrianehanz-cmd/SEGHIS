<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS\Services;

use App\Infrastructure\ExternalAPI\SegHIS\Client\SegHISHttpClient;

class SegHISDepartmentService
{
    public function __construct(
        private readonly SegHISHttpClient $client
    ) {
    }

    public function getAll(): mixed
    {
        return $this->client->get(
            '/department/show/'
        );
    }
}