<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface PrescriptionRepositoryInterface
{
    public function getAll(): mixed;

    public function getByEncounter(
        string|int $encounterNumber
    ): mixed;

    public function getByReference(
        string $referenceNumber
    ): mixed;
}