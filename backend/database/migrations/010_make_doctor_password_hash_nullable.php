<?php

declare(strict_types=1);

return function (\PDO $pdo): void {
    $pdo->exec('ALTER TABLE doctors MODIFY password_hash VARCHAR(255) NULL');
};
