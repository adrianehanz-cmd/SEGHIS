<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Doctors\GetDoctorsUseCase;
use Throwable;

final class DoctorController extends ApiController
{
    public function __construct(
        private readonly GetDoctorsUseCase $getDoctors
    ) {
    }

    public function index(): void
    {
        try {
            $doctors = $this->getDoctors->execute();

            $this->success($doctors);
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }
}