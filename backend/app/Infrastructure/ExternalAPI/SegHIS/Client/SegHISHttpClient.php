<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS\Client;

use App\Infrastructure\ExternalAPI\SegHIS\Exceptions\SegHISApiException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

class SegHISHttpClient
{
    private Client $client;

    public function __construct()
    {
        $baseUrl = rtrim(
            $_ENV['SEGHIS_URL'] ?? '',
            '/'
        );

        if ($baseUrl === '') {
            throw new \RuntimeException(
                'SEGHIS_URL is not configured.'
            );
        }

        $this->client = new Client([
            'base_uri' => $baseUrl . '/',
            'timeout' => 30,
            'connect_timeout' => 10,
            'verify' => false,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    public function get(
        string $uri,
        array $query = []
    ): mixed {
        return $this->request(
            'GET',
            $uri,
            [
                'query' => $query,
            ]
        );
    }

    public function post(
        string $uri,
        array $data = []
    ): mixed {
        return $this->request(
            'POST',
            $uri,
            [
                'json' => $data,
            ]
        );
    }

    public function put(
        string $uri,
        array $data = []
    ): mixed {
        return $this->request(
            'PUT',
            $uri,
            [
                'json' => $data,
            ]
        );
    }

    public function delete(
        string $uri,
        array $data = []
    ): mixed {
        return $this->request(
            'DELETE',
            $uri,
            [
                'json' => $data,
            ]
        );
    }

    private function request(
        string $method,
        string $uri,
        array $options = []
    ): mixed {
        $uri = ltrim($uri, '/');

        $username = $_ENV['SEGHIS_USERNAME'] ?? '';
        $password = $_ENV['SEGHIS_PASSWORD'] ?? '';

        if ($username === '' || $password === '') {
            throw new \RuntimeException(
                'SegHIS credentials are not configured.'
            );
        }

        $options['auth'] = [
            $username,
            $password,
        ];

        try {
            $response = $this->client->request(
                $method,
                $uri,
                $options
            );
        } catch (GuzzleException $exception) {
            throw new SegHISApiException(
                'Unable to communicate with the SegHIS API.',
                0,
                $exception->getMessage()
            );
        }

        return $this->handleResponse($response);
    }

    private function handleResponse(
        ResponseInterface $response
    ): mixed {
        $statusCode = $response->getStatusCode();

        $body = (string) $response->getBody();

        $decoded = json_decode(
            $body,
            true
        );

        $responseData = json_last_error() === JSON_ERROR_NONE
            ? $decoded
            : $body;

        if ($statusCode < 200 || $statusCode >= 300) {
            $message = match ($statusCode) {
                400 => 'SegHIS rejected the request.',
                401 => 'SegHIS authentication failed.',
                403 => 'SegHIS denied access to the resource.',
                404 => 'SegHIS resource was not found.',
                500 => 'SegHIS returned an internal server error.',
                default => 'SegHIS returned an unexpected error.',
            };

            throw new SegHISApiException(
                $message,
                $statusCode,
                $responseData
            );
        }

        return $responseData;
    }
}