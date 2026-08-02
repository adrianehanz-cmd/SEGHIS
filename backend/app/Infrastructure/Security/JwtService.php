<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Firebase\JWT\ExpiredException;
use Throwable;

final class JwtService
{
    private string $secret;

    public function __construct()
    {
        $this->secret = $_ENV['JWT_SECRET'];
    }

    public function generate(array $payload): string
    {
        $time = time();

        return JWT::encode(
            [
                'iat' => $time,
                'exp' => $time + 3600,
                ...$payload
            ],
            $this->secret,
            'HS256'
        );
    }

    public function decode(string $token): object
    {
        return JWT::decode(
            $token,
            new Key(
                $this->secret,
                'HS256'
            )
        );
    }

    public function validate(string $token): bool
    {
        try {

            $this->decode($token);

            return true;

        } catch (ExpiredException) {

            return false;

        } catch (Throwable) {

            return false;

        }
    }

    public function payload(string $token): object
    {
        return $this->decode($token);
    }
}