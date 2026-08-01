<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Patients\GetPatientUseCase;
use App\Application\UseCases\Patients\GetPatientsUseCase;
use App\Application\UseCases\Patients\SearchPatientsUseCase;
use Throwable;

final class PatientController extends ApiController
{
    public function __construct(
        private readonly GetPatientsUseCase $getPatients,
        private readonly GetPatientUseCase $getPatient,
        private readonly SearchPatientsUseCase $searchPatients
    ) {
    }

    public function index(): void
    {
        try {
            $patients = $this->getPatients->execute();

            $this->success($patients);
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }

    public function show(
        string $id
    ): void {
        try {
            $patient = $this->getPatient->execute($id);

            $this->success($patient);
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }

    public function search(): void
    {
        try {
            $firstName = trim(
                $_GET['first_name'] ?? ''
            );

            $lastName = trim(
                $_GET['last_name'] ?? ''
            );

            if (
                $firstName === '' &&
                $lastName === ''
            ) {
                $this->error(
                    'At least one search parameter is required.',
                    422
                );

                return;
            }

            $patients = $this->searchPatients->execute(
                $firstName,
                $lastName
            );

            $this->success($patients);
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }
}