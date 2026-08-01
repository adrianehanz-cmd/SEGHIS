<?php
// backend/database/test_auth_di.php
declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap/app.php';

use App\Application\UseCases\Auth\LoginUseCase;
use App\Application\UseCases\Auth\RefreshTokenUseCase;
use App\Application\UseCases\Auth\LogoutUseCase;
use App\Framework\Controllers\AuthController;

foreach ([LoginUseCase::class, RefreshTokenUseCase::class, LogoutUseCase::class, AuthController::class] as $class) {
    try {
        $container->get($class);
        echo "OK: {$class}" . PHP_EOL;
    } catch (\Throwable $e) {
        echo "FAIL: {$class} — {$e->getMessage()}" . PHP_EOL;
    }
}