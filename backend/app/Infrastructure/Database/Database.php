<?php

namespace App\Infrastructure\Database;

use PDO;

class Database
{
    private PDO $connection;

    public function __construct(array $config)
    {
        $dsn = sprintf(
            "mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4",
            $config['host'],
            $config['port'],
            $config['database']
        );

        $this->connection = new PDO(
            $dsn,
            $config['username'],
            $config['password'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function connection(): PDO
    {
        return $this->connection;
    }
}