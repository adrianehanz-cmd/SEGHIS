<?php
declare(strict_types=1);
return function (\PDO $pdo): void { $pdo->exec("ALTER TABLE appointments ADD status VARCHAR(20) NOT NULL DEFAULT 'scheduled', ADD reminder_sent_at TIMESTAMP NULL DEFAULT NULL, ADD INDEX idx_appointments_status (status)"); };
