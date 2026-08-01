<?php

declare(strict_types=1);

require_once dirname(__DIR__) . '/bootstrap/app.php';

use App\Application\UseCases\Patients\GetPatientsUseCase;

try {
    $useCase = $container->get(
        GetPatientsUseCase::class
    );

    $patients = $useCase->execute();

    echo json_encode(
        [
            'success' => true,
            'data' => $patients,
        ],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    echo PHP_EOL;
} catch (Throwable $exception) {
    echo json_encode(
        [
            'success' => false,
            'message' => $exception->getMessage(),
            'type' => get_class($exception),
        ],
        JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
    );

    echo PHP_EOL;

    exit(1);
}