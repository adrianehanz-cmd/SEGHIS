<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS\Services;

use App\Infrastructure\ExternalAPI\SegHIS\Client\SegHISHttpClient;

class SegHISEncounterService
{
    public function __construct(
        private readonly SegHISHttpClient $client
    ) {
    }

    public function getAll(): mixed
    {
        return $this->client->get(
            '/encounter/show/'
        );
    }

    public function getById(
        string|int $id
    ): mixed {
        return $this->client->get(
            '/encounter/show/id/' . rawurlencode((string) $id)
        );
    }

    public function getByPatientId(
        string|int $patientId
    ): mixed {
        return $this->client->get(
            '/encounter/show/pid/'
            . rawurlencode((string) $patientId)
        );
    }

    public function getByDepartment(
        string $departmentId
    ): mixed {
        return $this->client->get(
            '/encounter/show/deptid/'
            . rawurlencode($departmentId)
        );
    }

    public function getByPatientName(
        string $firstName,
        string $lastName
    ): mixed {
        return $this->client->get(
            '/encounter/show/name_first/'
            . rawurlencode($firstName)
            . '/name_last/'
            . rawurlencode($lastName)
        );
    }

    public function getByDoctor(
        string|int $doctorNumber
    ): mixed {
        return $this->client->get(
            '/encounter/tagged/doctor_nr/'
            . rawurlencode((string) $doctorNumber)
        );
    }

    public function getByEncounterDate(
        string $date
    ): mixed {
        return $this->client->get(
            '/encounter/show/encounter_date/'
            . rawurlencode($date)
        );
    }

    public function getByDischargeDate(
        string $date
    ): mixed {
        return $this->client->get(
            '/encounter/show/discharge_date/'
            . rawurlencode($date)
        );
    }

    public function getByDateRange(
        string $encounterDate,
        string $dischargeDate
    ): mixed {
        return $this->client->get(
            '/encounter/show/encounter_date/'
            . rawurlencode($encounterDate)
            . '/discharge_date/'
            . rawurlencode($dischargeDate)
        );
    }

    public function tagPatient(
        array $data
    ): mixed {
        return $this->client->post(
            '/encounter/tagpatient/',
            $data
        );
    }

    public function untagPatient(
        array $data
    ): mixed {
        return $this->client->put(
            '/encounter/untagpatient/',
            $data
        );
    }

    public function referPatient(
        array $data
    ): mixed {
        return $this->client->post(
            '/encounter/referral/',
            $data
        );
    }
}