<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Encounter\GetEncounterUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class EncounterController
{
    public function __construct(
        private readonly GetEncounterUseCase $useCase
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->useCase->execute(),
            'Encounters retrieved successfully.'
        );
    }

    public function show(Request $request): void
    {
        $id = (string) $request->query()['id'];

        Response::json(
            $this->useCase->find($id),
            'Encounter retrieved successfully.'
        );
    }
}