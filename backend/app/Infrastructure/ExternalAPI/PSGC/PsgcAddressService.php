<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\PSGC;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

final class PsgcAddressService
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://psgc.cloud/api/',
            'timeout' => 15,
            'headers' => ['Accept' => 'application/json'],
        ]);
    }

    public function regions(): array
    {
        return $this->get('regions');
    }

    public function provinces(string $regionCode): array
    {
        return $this->get("regions/{$regionCode}/provinces");
    }

    public function municipalities(string $regionCode, string $provinceCode = ''): array
    {
        if ($provinceCode !== '') {
            return $this->get("provinces/{$provinceCode}/cities-municipalities");
        }

        return $this->get("regions/{$regionCode}/cities-municipalities");
    }

    public function barangays(string $municipalityCode): array
    {
        return $this->get("cities-municipalities/{$municipalityCode}/barangays");
    }

    private function get(string $path): array
    {
        try {
            $response = $this->client->get($path);
            $payload = json_decode((string) $response->getBody(), true);
            $items = is_array($payload['data'] ?? null) ? $payload['data'] : $payload;

            return is_array($items) ? array_values(array_filter($items, 'is_array')) : [];
        } catch (GuzzleException) {
            return [];
        }
    }
}
