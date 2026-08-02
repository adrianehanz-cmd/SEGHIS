<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Departments\GetDepartmentsUseCase;
use App\Framework\Http\Response;

final class DepartmentController
{
    public function __construct(
        private readonly GetDepartmentsUseCase $useCase
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->useCase->execute(),
            'Departments retrieved successfully.'
        );
    }
}