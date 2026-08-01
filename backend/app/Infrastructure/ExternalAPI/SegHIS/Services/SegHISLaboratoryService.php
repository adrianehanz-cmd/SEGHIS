<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS\Services;

use App\Infrastructure\ExternalAPI\SegHIS\Client\SegHISHttpClient;

class SegHISLaboratoryService
{
    public function __construct(
        private readonly SegHISHttpClient $client
    ) {
    }

    public function getAllResults(): mixed
    {
        return $this->client->get(
            '/laboratory/labresult/'
        );
    }

    public function getResultsByPatient(
        string $lastName,
        string $firstName
    ): mixed {
        $patient = $lastName . ',' . $firstName;

        return $this->client->get(
            '/laboratory/labresult/patient/'
            . rawurlencode($patient)
        );
    }

    public function getResultsByPatientAndYear(
        string $lastName,
        string $firstName,
        string|int $year
    ): mixed {
        $patient = $lastName . ',' . $firstName;

        return $this->client->get(
            '/laboratory/labresult/patient/'
            . rawurlencode($patient)
            . '/period/'
            . rawurlencode((string) $year)
        );
    }

    public function getResultsByHrn(
        string|int $hrn
    ): mixed {
        return $this->client->get(
            '/laboratory/labresult/hrn/'
            . rawurlencode((string) $hrn)
        );
    }

    public function createOrder(
        array $data
    ): mixed {
        return $this->client->post(
            '/laboratory/createorder/',
            $data
        );
    }

    public function updateOrder(
        string|int $orderNumber,
        array $data
    ): mixed {
        return $this->client->put(
            '/laboratory/updateorder/id/'
            . rawurlencode((string) $orderNumber),
            $data
        );
    }
}