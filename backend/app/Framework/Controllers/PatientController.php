<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Patients\GetPatientsUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class PatientController
{
    public function __construct(
        private readonly GetPatientsUseCase $useCase
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->useCase->execute(),
            'Patients retrieved successfully.'
        );
    }

    public function show(Request $request): void
    {
        $id = (string) $request->query()['id'];

        Response::json(
            $this->useCase->find($id),
            'Patient retrieved successfully.'
        );
    }

    public function search(Request $request): void
    {
        $keyword = (string) $request->query()['keyword'];

        Response::json(
            $this->useCase->search($keyword),
            'Patients retrieved successfully.'
        );
    }
}