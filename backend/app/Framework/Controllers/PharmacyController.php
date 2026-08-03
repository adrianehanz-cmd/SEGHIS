<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Pharmacy\GetPharmacyUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class PharmacyController
{
    public function __construct(
        private readonly GetPharmacyUseCase $useCase
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->useCase->execute(),
            'Pharmacies retrieved successfully.'
        );
    }

    public function show(Request $request): void
    {
        $id = (string) $request->query()['id'];

        Response::json(
            $this->useCase->find($id),
            'Pharmacy retrieved successfully.'
        );
    }
}