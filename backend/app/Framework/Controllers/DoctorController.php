<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Doctors\GetDoctorsUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class DoctorController
{
    public function __construct(
        private readonly GetDoctorsUseCase $useCase
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->useCase->execute(),
            'Doctors retrieved successfully.'
        );
    }

    public function show(Request $request): void
    {
        $id = (string) $request->query()['id'];

        Response::json(
            $this->useCase->find($id),
            'Doctor retrieved successfully.'
        );
    }
}