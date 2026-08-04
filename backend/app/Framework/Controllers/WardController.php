<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Departments\GetDepartmentsUseCase;
use App\Application\UseCases\Ward\GetWardUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

final class WardController
{
    public function __construct(
        private readonly GetWardUseCase $useCase,
        private readonly GetDepartmentsUseCase $departments
    ) {
    }

    public function index(): void
    {
        Response::json(
            $this->withDepartmentNames($this->useCase->execute()),
            'Ward records retrieved successfully.'
        );
    }

    public function show(Request $request): void
    {
        $id = (string) $request->query()['id'];

        Response::json(
            $this->withDepartmentNames($this->useCase->find($id)),
            'Ward record retrieved successfully.'
        );
    }

    private function withDepartmentNames(array $result): array
    {
        $departmentResult = $this->departments->execute();
        $departmentItems = is_array($departmentResult['data'] ?? null) ? $departmentResult['data'] : $departmentResult;
        $departmentNames = [];

        foreach ($departmentItems as $department) {
            if (!is_array($department) || !isset($department['deptid'])) {
                continue;
            }
            $departmentNames[(string) $department['deptid']] = $department['name_formal'] ?? $department['name'] ?? $department['name_short'] ?? null;
        }

        $items = is_array($result['data'] ?? null) ? $result['data'] : $result;
        $items = array_map(static function (mixed $ward) use ($departmentNames): mixed {
            if (!is_array($ward)) {
                return $ward;
            }
            $departmentId = (string) ($ward['deptid'] ?? $ward['department_id'] ?? '');
            return $ward + ['department_name' => $departmentNames[$departmentId] ?? null];
        }, $items);

        return isset($result['data']) && is_array($result['data']) ? [...$result, 'data' => $items] : $items;
    }
}
