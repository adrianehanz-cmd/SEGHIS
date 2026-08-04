<?php

declare(strict_types=1);

return function (\PDO $pdo): void {
    $pdo->exec(<<<'SQL'
        CREATE TABLE IF NOT EXISTS patients (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            pid VARCHAR(20) NOT NULL UNIQUE,
            date_registered DATETIME NULL,
            name_last VARCHAR(100) NOT NULL,
            name_first VARCHAR(100) NOT NULL,
            name_middle VARCHAR(100) NULL,
            date_birth DATE NULL,
            age SMALLINT UNSIGNED NULL,
            sex ENUM('m', 'f', 'o') NULL,
            civil_status VARCHAR(50) NULL,
            place_birth VARCHAR(255) NULL,
            street1 VARCHAR(255) NULL,
            barangay VARCHAR(150) NULL,
            city VARCHAR(150) NULL,
            province VARCHAR(150) NULL,
            country VARCHAR(100) NULL,
            zip_code VARCHAR(15) NULL,
            ethnic VARCHAR(100) NULL,
            religion VARCHAR(150) NULL,
            mother_of_patient VARCHAR(255) NULL,
            father_of_patient VARCHAR(255) NULL,
            spouse_of_patient VARCHAR(255) NULL,
            death_date DATE NULL,
            brgy_code VARCHAR(20) NULL,
            brgy_code_10 VARCHAR(20) NULL,
            municity_code VARCHAR(20) NULL,
            municity_code_10 VARCHAR(20) NULL,
            province_code VARCHAR(20) NULL,
            province_code_10 VARCHAR(20) NULL,
            region_code VARCHAR(20) NULL,
            region_code_10 VARCHAR(20) NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_patients_name (name_last, name_first),
            INDEX idx_patients_birth (date_birth),
            INDEX idx_patients_registered (date_registered)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    SQL);
};
