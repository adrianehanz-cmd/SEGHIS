<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Ward\GetWardUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class WardController
{
    public function __construct(
        private readonly GetWardUseCase $useCase
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->useCase->execute(),
            'Ward records retrieved successfully.'
        );
    }

    public function show(Request $request): void
    {
        $id = (string) $request->query()['id'];

        Response::json(
            $this->useCase->find($id),
            'Ward record retrieved successfully.'
        );
    }
}