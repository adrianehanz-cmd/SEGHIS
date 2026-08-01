<?php

declare(strict_types=1);

use PDO;

return function (PDO $pdo): void {
    $pdo->exec(
        <<<'SQL'
        CREATE TABLE sessions (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            token_jti VARCHAR(255) NOT NULL UNIQUE,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT NULL,
            expires_at TIMESTAMP NOT NULL,
            revoked_at TIMESTAMP NULL DEFAULT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

            CONSTRAINT fk_sessions_user
                FOREIGN KEY (user_id)
                REFERENCES users(id)
                ON UPDATE CASCADE
                ON DELETE CASCADE,

            INDEX idx_sessions_user_id (user_id),
            INDEX idx_sessions_expires_at (expires_at),
            INDEX idx_sessions_revoked_at (revoked_at)
        ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8mb4
          COLLATE=utf8mb4_unicode_ci
        SQL
    );
};