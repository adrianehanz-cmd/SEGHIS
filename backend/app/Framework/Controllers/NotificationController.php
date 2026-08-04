<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Framework\Http\Request;
use App\Framework\Http\Response;
use PDO;

final class NotificationController
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function index(Request $request): void
    {
        $statement = $this->pdo->prepare('SELECT id, type, title, message, is_read, created_at FROM notifications WHERE user_id = :user_id AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 50');
        $statement->execute(['user_id' => $request->user()['id']]);
        Response::json($statement->fetchAll(), 'Notifications retrieved successfully.');
    }

    public function markAllRead(Request $request): void
    {
        $statement = $this->pdo->prepare('UPDATE notifications SET is_read = TRUE, read_at = CURRENT_TIMESTAMP WHERE user_id = :user_id AND is_read = FALSE AND deleted_at IS NULL');
        $statement->execute(['user_id' => $request->user()['id']]);
        Response::json(['updated' => $statement->rowCount()], 'Notifications marked as read.');
    }
}
