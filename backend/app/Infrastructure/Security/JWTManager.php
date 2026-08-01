<?php

namespace App\Infrastructure\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Ramsey\Uuid\Uuid;

class JWTManager
{
    private const ACCESS_TTL = 900;      // 15 minutes
    private const REFRESH_TTL = 604800;  // 7 days

    public function generateAccessToken(array $claims): array
    {
        return $this->issue($claims, self::ACCESS_TTL, 'access');
    }

    public function generateRefreshToken(array $claims): array
    {
        return $this->issue($claims, self::REFRESH_TTL, 'refresh');
    }

    private function issue(array $claims, int $ttl, string $type): array
    {
        $jti = Uuid::uuid4()->toString();
        $now = time();

        $payload = array_merge($claims, [
            'jti' => $jti,
            'type' => $type,
            'iat' => $now,
            'exp' => $now + $ttl,
        ]);

        $token = JWT::encode($payload, $_ENV['JWT_SECRET'], 'HS256');

        return [
            'token' => $token,
            'jti' => $jti,
            'expires_at' => $now + $ttl,
        ];
    }

    public function verify(string $token): object
    {
        return JWT::decode($token, new Key($_ENV['JWT_SECRET'], 'HS256'));
    }

    public function accessTtl(): int
    {
        return self::ACCESS_TTL;
    }

    public function refreshTtl(): int
    {
        return self::REFRESH_TTL;
    }
}