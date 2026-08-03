<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Radiology\GetRadiologyUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class RadiologyController
{
    public function __construct(
        private readonly GetRadiologyUseCase $useCase
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->useCase->execute(),
            'Radiology records retrieved successfully.'
        );
    }

    public function show(Request $request): void
    {
        $id = (string) $request->query()['id'];

        Response::json(
            $this->useCase->find($id),
            'Radiology record retrieved successfully.'
        );
    }
}