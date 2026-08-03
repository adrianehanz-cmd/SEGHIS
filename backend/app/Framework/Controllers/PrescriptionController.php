<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Prescription\GetPrescriptionUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class PrescriptionController
{
    public function __construct(
        private readonly GetPrescriptionUseCase $useCase
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->useCase->execute(),
            'Prescriptions retrieved successfully.'
        );
    }

    public function show(Request $request): void
    {
        $id = (string) $request->query()['id'];

        Response::json(
            $this->useCase->find($id),
            'Prescription retrieved successfully.'
        );
    }
}