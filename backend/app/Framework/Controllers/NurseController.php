<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Nurses\GetNursesUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class NurseController
{
    public function __construct(
        private readonly GetNursesUseCase $useCase
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->useCase->execute(),
            'Nurses retrieved successfully.'
        );
    }

    public function show(Request $request): void
    {
        $id = (string) $request->query()['id'];

        Response::json(
            $this->useCase->find($id),
            'Nurse retrieved successfully.'
        );
    }
}