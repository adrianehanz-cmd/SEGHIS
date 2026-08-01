<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS\Services;

use App\Infrastructure\ExternalAPI\SegHIS\Client\SegHISHttpClient;

class SegHISNurseService
{
    public function __construct(
        private readonly SegHISHttpClient $client
    ) {
    }

    public function getAll(): mixed
    {
        return $this->client->get(
            '/nurse/show/'
        );
    }

    public function getById(
        string|int $id
    ): mixed {
        return $this->client->get(
            '/nurse/show/id/' . rawurlencode((string) $id)
        );
    }

    public function getByDepartment(
        string $departmentId
    ): mixed {
        return $this->client->get(
            '/nurse/show/deptid/'
            . rawurlencode($departmentId)
        );
    }

    public function getByName(
        string $firstName,
        string $lastName
    ): mixed {
        return $this->client->get(
            '/nurse/show/name_first/'
            . rawurlencode($firstName)
            . '/name_last/'
            . rawurlencode($lastName)
        );
    }
}