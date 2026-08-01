<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap/app.php';

use App\Infrastructure\ExternalAPI\SegHIS\Services\SegHISDepartmentService;

try {
    $service = $container->get(
        SegHISDepartmentService::class
    );

    $departments = $service->getAll();

    echo json_encode(
        [
            'success' => true,
            'data' => $departments,
        ],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    echo PHP_EOL;
} catch (Throwable $exception) {
    echo json_encode(
        [
            'success' => false,
            'message' => $exception->getMessage(),
            'status' => $exception->getCode(),
        ],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    echo PHP_EOL;

    exit(1);
}