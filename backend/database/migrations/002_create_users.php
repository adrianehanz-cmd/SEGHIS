<?php

declare(strict_types=1);

use PDO;

return function (PDO $pdo): void {
    $pdo->exec(
        <<<'SQL'
        CREATE TABLE users (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            role_id BIGINT UNSIGNED NOT NULL,
            username VARCHAR(100) NOT NULL UNIQUE,
            email VARCHAR(255) NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            first_name VARCHAR(100) NOT NULL,
            last_name VARCHAR(100) NOT NULL,
            is_active BOOLEAN NOT NULL DEFAULT TRUE,
            last_login_at TIMESTAMP NULL DEFAULT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,

            CONSTRAINT fk_users_role
                FOREIGN KEY (role_id)
                REFERENCES roles(id)
                ON UPDATE CASCADE
                ON DELETE RESTRICT,

            INDEX idx_users_role_id (role_id),
            INDEX idx_users_active (is_active),
            INDEX idx_users_deleted_at (deleted_at)
        ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8mb4
          COLLATE=utf8mb4_unicode_ci
        SQL
    );
};