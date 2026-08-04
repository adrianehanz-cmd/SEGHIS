<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Doctors\GetDoctorsUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use PDO;
use Throwable;

final class DoctorRecordsController
{
    public function __construct(private readonly PDO $pdo, private readonly GetDoctorsUseCase $seghisDoctors)
    {
    }

    public function index(Request $request): void
    {
        $page = max(1, (int) ($request->query()['page'] ?? 1)); $perPage = min(10, max(1, (int) ($request->query()['per_page'] ?? 10))); $search = trim((string) ($request->query()['search'] ?? ''));
        $local = $this->local($search); $seghis = $this->seghis($search); $records = [];
        foreach (array_merge($seghis, $local) as $doctor) { if (($doctor['personnel_nr'] ?? '') !== '') $records[(string) $doctor['personnel_nr']] = $doctor; }
        $records = array_values($records); usort($records, static fn (array $a, array $b) => strcmp($b['createdAt'] ?? $b['dateRegistered'] ?? '', $a['createdAt'] ?? $a['dateRegistered'] ?? ''));
        Response::json(['items' => array_slice($records, ($page - 1) * $perPage, $perPage), 'pagination' => ['page' => $page, 'per_page' => $perPage, 'total' => count($records), 'total_pages' => max(1, (int) ceil(count($records) / $perPage))], 'sources' => ['local' => count($local), 'seghis' => count($seghis)]], 'Doctors retrieved successfully.');
    }

    public function store(Request $request): void
    {
        $input = $request->json();
        foreach (['personnel_nr', 'name_first', 'name_last', 'login_id'] as $field) if (trim((string) ($input[$field] ?? '')) === '') Response::json(null, "{$field} is required.", 422);
        $fields = ['personnel_nr', 'pid', 'date_registered', 'name_last', 'name_first', 'name_middle', 'street1', 'city', 'province', 'country', 'zip_code', 'date_birth', 'sex', 'location_nr', 'deptid', 'name_formal', 'name_short', 'license_nr', 'prescription_license_nr', 'tin', 'ptr_nr', 's2_nr', 'login_id'];
        $payload = [];
        foreach ($fields as $field) { $value = $input[$field] ?? null; $payload[$field] = $value === '' ? null : $value; }
        $payload['password_hash'] = null;
        try {
            $columns = implode(', ', [...$fields, 'password_hash']); $placeholders = implode(', ', array_map(static fn (string $field) => ':' . $field, [...$fields, 'password_hash']));
            $statement = $this->pdo->prepare("INSERT INTO doctors ({$columns}) VALUES ({$placeholders})"); $statement->execute($payload);
        } catch (\PDOException $exception) {
            if ($exception->getCode() === '23000') Response::json(null, 'Personnel number or login ID already exists.', 409);
            throw $exception;
        }
        $statement = $this->pdo->prepare('SELECT * FROM doctors WHERE personnel_nr = :personnel_nr');
        $statement->execute(['personnel_nr' => $payload['personnel_nr']]);
        $doctor = $this->mapLocal($statement->fetch() ?: []);
        $notification = $this->pdo->prepare('INSERT INTO notifications (user_id, type, title, message) VALUES (:user_id, :type, :title, :message)');
        $notification->execute([
            'user_id' => $request->user()['id'],
            'type' => 'doctor_created',
            'title' => 'Doctor added',
            'message' => sprintf(
                'You added Dr. %s (Personnel No.: %s, Department: %s).',
                trim(($doctor['name_first'] ?? '') . ' ' . ($doctor['name_last'] ?? '')),
                $doctor['personnel_nr'] ?? '',
                $doctor['name_formal'] ?: ($doctor['deptid'] ?: 'Not assigned')
            ),
        ]);
        Response::json(['personnel_nr' => $payload['personnel_nr']], 'Doctor created successfully.', 201);
    }

    private function local(string $search): array
    {
        $sql = 'SELECT * FROM doctors'; $params = []; $terms = array_values(array_filter(array_map('trim', explode(',', $search))));
        if ($terms) { $where = []; foreach ($terms as $i => $term) { $where[] = "(personnel_nr LIKE :term{$i} OR pid LIKE :term{$i} OR name_first LIKE :term{$i} OR name_last LIKE :term{$i} OR license_nr LIKE :term{$i})"; $params["term{$i}"] = "%{$term}%"; } $sql .= ' WHERE ' . implode(' AND ', $where); }
        $statement = $this->pdo->prepare($sql); $statement->execute($params);
        return array_map(fn (array $row) => $this->mapLocal($row), $statement->fetchAll());
    }

    private function seghis(string $search): array
    {
        try { $result = $this->seghisDoctors->execute(); $items = is_array($result['data'] ?? null) ? $result['data'] : $result; $terms = array_values(array_filter(array_map('trim', explode(',', $search)))); return array_values(array_filter(array_map(static fn (array $item) => $item + ['source' => 'seghis'], array_values(array_filter($items, 'is_array'))), static function (array $item) use ($terms): bool { foreach ($terms as $term) { if (stripos(implode(' ', [(string) ($item['personnel_nr'] ?? ''), (string) ($item['name_first'] ?? ''), (string) ($item['name_last'] ?? ''), (string) ($item['license_nr'] ?? '')]), $term) === false) return false; } return true; })); } catch (Throwable) { return []; }
    }

    private function mapLocal(array $row): array
    {
        return ['personnel_nr' => $row['personnel_nr'], 'pid' => $row['pid'], 'dateRegistered' => $row['date_registered'], 'createdAt' => $row['created_at'], 'name_last' => $row['name_last'], 'name_first' => $row['name_first'], 'name_middle' => $row['name_middle'], 'Street1' => $row['street1'], 'City' => $row['city'], 'Province' => $row['province'], 'Country' => $row['country'], 'ZipCode' => $row['zip_code'], 'date_birth' => $row['date_birth'], 'sex' => $row['sex'], 'location_nr' => $row['location_nr'], 'deptid' => $row['deptid'], 'name_formal' => $row['name_formal'], 'name_short' => $row['name_short'], 'license_nr' => $row['license_nr'], 'prescription_license_nr' => $row['prescription_license_nr'], 'tin' => $row['tin'], 'ptr_nr' => $row['ptr_nr'], 's2_nr' => $row['s2_nr'], 'login_id' => $row['login_id'], 'source' => 'local'];
    }
}
