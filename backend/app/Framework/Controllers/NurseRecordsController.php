<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Nurses\GetNursesUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use PDO;
use Throwable;

final class NurseRecordsController
{
    public function __construct(private readonly PDO $pdo, private readonly GetNursesUseCase $seghisNurses) {}

    public function index(Request $request): void
    {
        $page = max(1, (int) ($request->query()['page'] ?? 1)); $perPage = min(10, max(1, (int) ($request->query()['per_page'] ?? 10))); $search = trim((string) ($request->query()['search'] ?? ''));
        $records = [];
        foreach (array_merge($this->seghis($search), $this->local($search)) as $nurse) if (($nurse['personnel_nr'] ?? '') !== '') $records[(string) $nurse['personnel_nr']] = $nurse;
        $records = array_values($records); usort($records, static fn (array $a, array $b) => strcmp($b['createdAt'] ?? '', $a['createdAt'] ?? ''));
        Response::json(['items' => array_slice($records, ($page - 1) * $perPage, $perPage), 'pagination' => ['page' => $page, 'per_page' => $perPage, 'total' => count($records), 'total_pages' => max(1, (int) ceil(count($records) / $perPage))]], 'Nurses retrieved successfully.');
    }

    public function store(Request $request): void
    {
        $input = $request->json(); foreach (['personnel_nr', 'name_first', 'name_last', 'login_id'] as $field) if (trim((string) ($input[$field] ?? '')) === '') Response::json(null, "{$field} is required.", 422);
        $fields = ['personnel_nr', 'name_last', 'name_first', 'name_middle', 'date_birth', 'sex', 'location_nr', 'deptid', 'name_formal', 'name_short', 'license_nr', 'tin', 'login_id', 'ward_area', 'all_ward']; $payload = [];
        foreach ($fields as $field) { $value = $input[$field] ?? null; $payload[$field] = $value === '' ? null : $value; }
        try { $statement = $this->pdo->prepare('INSERT INTO nurses (' . implode(', ', $fields) . ') VALUES (' . implode(', ', array_map(static fn (string $field) => ':' . $field, $fields)) . ')'); $statement->execute($payload); }
        catch (\PDOException $exception) { if ($exception->getCode() === '23000') Response::json(null, 'Personnel number or login ID already exists.', 409); throw $exception; }
        $notification = $this->pdo->prepare('INSERT INTO notifications (user_id, type, title, message) VALUES (:user_id, :type, :title, :message)');
        $notification->execute(['user_id' => $request->user()['id'], 'type' => 'nurse_created', 'title' => 'Nurse added', 'message' => sprintf('You added %s %s (Personnel No.: %s, Department: %s).', $payload['name_first'], $payload['name_last'], $payload['personnel_nr'], $payload['name_formal'] ?: ($payload['deptid'] ?: 'Not assigned'))]);
        Response::json(['personnel_nr' => $payload['personnel_nr']], 'Nurse created successfully.', 201);
    }

    private function local(string $search): array { $sql = 'SELECT * FROM nurses'; $params = []; if ($search !== '') { $sql .= ' WHERE personnel_nr LIKE :search OR name_first LIKE :search OR name_last LIKE :search OR license_nr LIKE :search'; $params['search'] = "%{$search}%"; } $statement = $this->pdo->prepare($sql); $statement->execute($params); return array_map(fn (array $nurse) => $this->map($nurse), $statement->fetchAll()); }
    private function seghis(string $search): array { try { $result = $this->seghisNurses->execute(); $items = is_array($result['data'] ?? null) ? $result['data'] : $result; return array_values(array_filter(array_map(static fn (array $nurse) => $nurse + ['source' => 'seghis'], array_values(array_filter($items, 'is_array'))), static fn (array $nurse) => $search === '' || stripos(implode(' ', [(string) ($nurse['personnel_nr'] ?? ''), (string) ($nurse['name_first'] ?? ''), (string) ($nurse['name_last'] ?? '')]), $search) !== false)); } catch (Throwable) { return []; } }
    private function map(array $nurse): array { return ['personnel_nr' => $nurse['personnel_nr'], 'name_last' => $nurse['name_last'], 'name_first' => $nurse['name_first'], 'name_middle' => $nurse['name_middle'], 'date_birth' => $nurse['date_birth'], 'sex' => $nurse['sex'], 'location_nr' => $nurse['location_nr'], 'deptid' => $nurse['deptid'], 'name_formal' => $nurse['name_formal'], 'name_short' => $nurse['name_short'], 'license_nr' => $nurse['license_nr'], 'tin' => $nurse['tin'], 'login_id' => $nurse['login_id'], 'ward_area' => $nurse['ward_area'], 'all_ward' => $nurse['all_ward'], 'createdAt' => $nurse['created_at'], 'source' => 'local']; }
}
