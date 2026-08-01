<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface LaboratoryRepositoryInterface
{
    public function getAllResults(): mixed;

    public function getByPatient(
        string $lastName,
        string $firstName
    ): mixed;

    public function getByHrn(
        string|int $hrn
    ): mixed;
}