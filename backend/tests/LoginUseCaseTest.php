<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$container = require dirname(__DIR__) . '/bootstrap/app.php';

$useCase = $container->get(
    \App\Application\UseCases\Auth\LoginUseCase::class
);

$request = new \App\Application\DTOs\LoginRequest(
    'admin',
    'ChangeMe123!'
);

try {

    $response = $useCase->execute($request);

    print_r($response);

} catch (Throwable $e) {

    echo $e->getMessage();

}