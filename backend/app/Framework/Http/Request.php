<?php

declare(strict_types=1);

namespace App\Framework\Http;

class Request
{
    private ?array $user = null;

    public function method(): string
    {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public function uri(): string
    {
        return parse_url(
            $_SERVER['REQUEST_URI'] ?? '/',
            PHP_URL_PATH
        ) ?: '/';
    }

    public function headers(): array
    {
        if (function_exists('getallheaders')) {
            return getallheaders();
        }

        return [];
    }

    public function header(string $name): ?string
    {
        foreach ($this->headers() as $key => $value) {
            if (strcasecmp($key, $name) === 0) {
                return $value;
            }
        }

        return null;
    }

    public function json(): array
    {
        $content = file_get_contents('php://input');

        if ($content === false || trim($content) === '') {
            return [];
        }

        $data = json_decode($content, true);

        return is_array($data) ? $data : [];
    }

    public function query(): array
    {
        return $_GET;
    }

    public function input(string $key, mixed $default = null): mixed
    {
        $json = $this->json();

        return $json[$key]
            ?? $_POST[$key]
            ?? $_GET[$key]
            ?? $default;
    }

    public function bearerToken(): ?string
    {
        $header = $this->header('Authorization');

        if (!$header) {
            return null;
        }

        if (preg_match('/Bearer\s+(.+)/i', $header, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    /**
     * Store the authenticated user.
     */
    public function setUser(array $user): void
    {
        $this->user = $user;
    }

    /**
     * Get the authenticated user.
     */
    public function user(): ?array
    {
        return $this->user;
    }
}