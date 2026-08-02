<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface DepartmentRepositoryInterface
{
    /**
     * Retrieve all departments.
     */
    public function all(): array;
}