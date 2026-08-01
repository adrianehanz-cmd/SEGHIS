<?php

declare(strict_types=1);

use PDO;

return function (PDO $pdo): void {
    $pdo->exec(
        <<<'SQL'
        CREATE TABLE audit_logs (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NULL,
            action VARCHAR(100) NOT NULL,
            entity_type VARCHAR(100) NULL,
            entity_id VARCHAR(100) NULL,
            old_values JSON NULL,
            new_values JSON NULL,
            ip_address VARCHAR(45) NULL,
            user_agent TEXT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

            CONSTRAINT fk_audit_logs_user
                FOREIGN KEY (user_id)
                REFERENCES users(id)
                ON UPDATE CASCADE
                ON DELETE SET NULL,

            INDEX idx_audit_logs_user_id (user_id),
            INDEX idx_audit_logs_action (action),
            INDEX idx_audit_logs_entity (
                entity_type,
                entity_id
            ),
            INDEX idx_audit_logs_created_at (created_at)
        ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8mb4
          COLLATE=utf8mb4_unicode_ci
        SQL
    );
};