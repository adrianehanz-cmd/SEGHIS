<?php

declare(strict_types=1);

namespace App\Infrastructure\Logging;

use App\Infrastructure\Database\Database;

class AuditLogger
{
    public function __construct(
        private readonly Database $database
    ) {
    }

    public function log(
        ?int $userId,
        string $action,
        ?string $entityType = null,
        string|int|null $entityId = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        $statement = $this->database->connection()->prepare(
            'INSERT INTO audit_logs
                (user_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent)
             VALUES
                (:user_id, :action, :entity_type, :entity_id, :old_values, :new_values, :ip, :agent)'
        );

        $statement->execute([
            'user_id' => $userId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId !== null ? (string) $entityId : null,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            'agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
    }
}
