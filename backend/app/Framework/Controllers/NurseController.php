<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Nurses\GetNursesUseCase;
use Throwable;

final class NurseController extends ApiController
{
    public function __construct(
        private readonly GetNursesUseCase $getNurses
    ) {
    }

    public function index(): void
    {
        try {
            $nurses = $this->getNurses->execute();

            $this->success($nurses);
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }
}