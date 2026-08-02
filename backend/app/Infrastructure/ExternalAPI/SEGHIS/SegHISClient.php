<?php

declare(strict_types=1);

namespace App\Infrastructure\ExternalAPI\SegHIS;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use RuntimeException;

final class SegHISClient
{
    private Client $client;


    public function __construct()
    {
        $this->client = new Client([

            'base_uri' => rtrim(
                $_ENV['SEGHIS_URL'],
                '/'
            ) . '/',

            'timeout' => 30,

            'verify' => false,

            'auth' => [

                $_ENV['SEGHIS_USERNAME'],

                $_ENV['SEGHIS_PASSWORD']

            ],

            'headers' => [

                'Accept' => 'application/json',

                'User-Agent' =>
                    'SegHIS Patient Portal'

            ],

        ]);
    }



    public function get(
        string $endpoint,
        array $query = []
    ): array {


        try {


            $response = $this->client->get(

                ltrim(
                    $endpoint,
                    '/'
                ),

                [

                    'query' => $query

                ]

            );


            return $this->decode(

                $response
                    ->getBody()
                    ->getContents()

            );


        } catch (GuzzleException $exception) {


            throw new RuntimeException(

                'SegHIS GET failed: '
                .
                $exception->getMessage()

            );

        }

    }





    public function post(
        string $endpoint,
        array $payload = []
    ): array {


        try {


            $response = $this->client->post(

                ltrim(
                    $endpoint,
                    '/'
                ),

                [

                    'json' => $payload

                ]

            );


            return $this->decode(

                $response
                    ->getBody()
                    ->getContents()

            );


        } catch (GuzzleException $exception) {


            throw new RuntimeException(

                'SegHIS POST failed: '
                .
                $exception->getMessage()

            );

        }

    }





    private function decode(
        string $content
    ): array {


        $data = json_decode(

            $content,

            true

        );


        if (!is_array($data)) {


            return [];


        }


        return $data;

    }

}