<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface EncounterRepositoryInterface
{
    /**
     * Retrieve all encounters.
     */
    public function all(): array;

    /**
     * Find encounter by ID.
     */
    public function find(string $encounterId): array;
}