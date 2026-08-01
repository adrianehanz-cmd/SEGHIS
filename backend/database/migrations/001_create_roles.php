<?php

declare(strict_types=1);

use PDO;

return function (PDO $pdo): void {
    $pdo->exec(
        <<<'SQL'
        CREATE TABLE roles (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL UNIQUE,
            description VARCHAR(255) NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL
        ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8mb4
          COLLATE=utf8mb4_unicode_ci
        SQL
    );

    $statement = $pdo->prepare(
        'INSERT INTO roles (name, description)
         VALUES (:name, :description)'
    );

    $roles = [
        [
            'name' => 'administrator',
            'description' => 'Full system access',
        ],
        [
            'name' => 'doctor',
            'description' => 'Doctor access',
        ],
        [
            'name' => 'nurse',
            'description' => 'Nurse access',
        ],
    ];

    foreach ($roles as $role) {
        $statement->execute($role);
    }
};