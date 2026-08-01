<?php

declare(strict_types=1);

use PDO;

return function (PDO $pdo): void {
    $pdo->exec(
        <<<'SQL'
        CREATE TABLE notifications (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            type VARCHAR(100) NOT NULL,
            title VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            is_read BOOLEAN NOT NULL DEFAULT FALSE,
            read_at TIMESTAMP NULL DEFAULT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
                ON UPDATE CURRENT_TIMESTAMP,
            deleted_at TIMESTAMP NULL DEFAULT NULL,

            CONSTRAINT fk_notifications_user
                FOREIGN KEY (user_id)
                REFERENCES users(id)
                ON UPDATE CASCADE
                ON DELETE CASCADE,

            INDEX idx_notifications_user_id (user_id),
            INDEX idx_notifications_is_read (is_read),
            INDEX idx_notifications_created_at (created_at)
        ) ENGINE=InnoDB
          DEFAULT CHARSET=utf8mb4
          COLLATE=utf8mb4_unicode_ci
        SQL
    );
};