<?php

declare(strict_types=1);

return function (\PDO $pdo): void {
    $exists = $pdo->query("SHOW INDEX FROM patients WHERE Key_name = 'uq_patients_identity'")->fetch();
    if (!$exists) {
        $pdo->exec('ALTER TABLE patients ADD UNIQUE KEY uq_patients_identity (name_first, name_last, date_birth)');
    }
};
