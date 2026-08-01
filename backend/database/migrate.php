<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap/app.php';

use App\Infrastructure\Database\Database;

$database = $container->get(Database::class);

$pdo = $database->connection();

$migrationsTable = <<<'SQL'
CREATE TABLE IF NOT EXISTS migrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB
  DEFAULT CHARSET=utf8mb4
  COLLATE=utf8mb4_unicode_ci
SQL;

$pdo->exec($migrationsTable);

$files = glob(__DIR__ . '/migrations/*.php');

sort($files);

$executed = $pdo
    ->query('SELECT migration FROM migrations')
    ->fetchAll(\PDO::FETCH_COLUMN);

foreach ($files as $file) {
    $migrationName = basename($file);

    if (in_array($migrationName, $executed, true)) {
        continue;
    }

    echo "Running: {$migrationName}" . PHP_EOL;

    $migration = require $file;

    if (!is_callable($migration)) {
        throw new RuntimeException(
            "Migration {$migrationName} must return a callable."
        );
    }

    try {
        $migration($pdo);

        $statement = $pdo->prepare(
            'INSERT INTO migrations (migration) VALUES (:migration)'
        );

        $statement->execute([
            'migration' => $migrationName,
        ]);

        echo "Completed: {$migrationName}" . PHP_EOL;
    } catch (\Throwable $exception) {
        echo "Failed: {$migrationName}" . PHP_EOL;
        echo $exception->getMessage() . PHP_EOL;

        exit(1);
    }
}

echo 'All migrations completed.' . PHP_EOL;