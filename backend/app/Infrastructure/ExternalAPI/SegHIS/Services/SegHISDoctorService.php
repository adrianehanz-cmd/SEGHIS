<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS\Services;

use App\Infrastructure\ExternalAPI\SegHIS\Client\SegHISHttpClient;

class SegHISDoctorService
{
    public function __construct(
        private readonly SegHISHttpClient $client
    ) {
    }

    public function getAll(): mixed
    {
        return $this->client->get(
            '/doctor/show/'
        );
    }

    public function getById(
        string|int $id
    ): mixed {
        return $this->client->get(
            '/doctor/show/id/' . rawurlencode((string) $id)
        );
    }

    public function getByDepartment(
        string $departmentId
    ): mixed {
        return $this->client->get(
            '/doctor/show/deptid/'
            . rawurlencode($departmentId)
        );
    }

    public function getByName(
        string $firstName,
        string $lastName
    ): mixed {
        return $this->client->get(
            '/doctor/show/name_first/'
            . rawurlencode($firstName)
            . '/name_last/'
            . rawurlencode($lastName)
        );
    }

    public function getNotes(
        string $type,
        string|int $doctorNumber,
        string|int $encounterNumber
    ): mixed {
        return $this->client->get(
            '/doctor/notes/type/'
            . rawurlencode($type)
            . '/doctor_nr/'
            . rawurlencode((string) $doctorNumber)
            . '/encounter_nr/'
            . rawurlencode((string) $encounterNumber)
        );
    }
}