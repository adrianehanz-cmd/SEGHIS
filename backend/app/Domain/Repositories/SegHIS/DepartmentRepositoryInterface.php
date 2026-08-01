<?php

declare(strict_types=1);

namespace App\Domain\Repositories\SegHIS;

interface DepartmentRepositoryInterface
{
    public function getAll(): mixed;
}