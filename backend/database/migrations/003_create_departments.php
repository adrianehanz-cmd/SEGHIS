<?php

declare(strict_types=1);

use PDO;

return function (PDO $pdo): void {
    $pdo->exec(
        <<<'SQL'
        CREATE TABLE departments (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            seghis_id VARCHAR(100) NULL UNIQUE,
            name VARCHAR(255) NOT NULL,
            description TEXT NULL,
            is_active BOOLEAN NOT NULL DEFAULT TRUE,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,

            INDEX idx_departments_name (name),
            INDEX idx_departments_active (is_active)
        ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8mb4
          COLLATE=utf8mb4_unicode_ci
        SQL
    );
};