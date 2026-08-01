<?php

namespace App\Infrastructure\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTManager
{
    public function generate(array $payload): string
    {
        return JWT::encode(
            $payload,
            $_ENV['JWT_SECRET'],
            'HS256'
        );
    }

    public function verify(string $token): object
    {
        return JWT::decode(
            $token,
            new Key($_ENV['JWT_SECRET'], 'HS256')
        );
    }
}