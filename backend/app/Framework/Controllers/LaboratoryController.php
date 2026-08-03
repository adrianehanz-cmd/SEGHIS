<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Laboratory\GetLaboratoryUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class LaboratoryController
{
    public function __construct(
        private readonly GetLaboratoryUseCase $useCase
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->useCase->execute(),
            'Laboratories retrieved successfully.'
        );
    }

    public function show(Request $request): void
    {
        $id = (string) $request->query()['id'];

        Response::json(
            $this->useCase->find($id),
            'Laboratory retrieved successfully.'
        );
    }
}