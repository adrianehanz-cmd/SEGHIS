<?php

declare(strict_types=1);

return function (\PDO $pdo): void {
    $pdo->exec(<<<'SQL'
        CREATE TABLE IF NOT EXISTS doctors (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            personnel_nr VARCHAR(30) NOT NULL UNIQUE,
            pid VARCHAR(30) NULL,
            date_registered DATETIME NULL,
            name_last VARCHAR(100) NOT NULL,
            name_first VARCHAR(150) NOT NULL,
            name_middle VARCHAR(100) NULL,
            street1 VARCHAR(255) NULL,
            city VARCHAR(150) NULL,
            province VARCHAR(150) NULL,
            country VARCHAR(100) NULL DEFAULT 'Philippines',
            zip_code VARCHAR(15) NULL,
            date_birth DATE NULL,
            sex ENUM('m', 'f', 'o') NULL,
            location_nr VARCHAR(30) NULL,
            deptid VARCHAR(100) NULL,
            name_formal VARCHAR(255) NULL,
            name_short VARCHAR(100) NULL,
            license_nr VARCHAR(100) NULL,
            prescription_license_nr VARCHAR(100) NULL,
            tin VARCHAR(30) NULL,
            ptr_nr VARCHAR(100) NULL,
            s2_nr VARCHAR(100) NULL,
            login_id VARCHAR(100) NOT NULL UNIQUE,
            password_hash VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_doctors_name (name_last, name_first),
            INDEX idx_doctors_department (deptid)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    SQL);
};
