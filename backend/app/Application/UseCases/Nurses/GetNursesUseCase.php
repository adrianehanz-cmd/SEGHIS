<?php

declare(strict_types=1);

namespace App\Application\UseCases\Nurses;

use App\Domain\Repositories\SegHIS\NurseRepositoryInterface;

class GetNursesUseCase
{
    public function __construct(
        private readonly NurseRepositoryInterface $repository
    ) {
    }

    public function execute(): mixed
    {
        return $this->repository->getAll();
    }
}