<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Departments\GetDepartmentsUseCase;
use Throwable;

final class DepartmentController extends ApiController
{
    public function __construct(
        private readonly GetDepartmentsUseCase $getDepartments
    ) {
    }

    public function index(): void
    {
        try {
            $departments = $this->getDepartments->execute();

            $this->success($departments);
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }
}