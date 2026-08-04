<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\UseCases\Patients\GetPatientsUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use PDO;
use Throwable;

final class PatientRecordsController
{
    public function __construct(
        private readonly PDO $pdo,
        private readonly GetPatientsUseCase $segHisPatients
    ) {
    }

    public function index(Request $request): void
    {
        $page = max(1, (int) ($request->query()['page'] ?? 1));
        $perPage = min(10, max(1, (int) ($request->query()['per_page'] ?? 10)));
        $search = trim((string) ($request->query()['search'] ?? ''));

        $local = $this->localPatients($search);
        $seghis = $this->segHisPatients($search);
        $combined = $this->mergePatients($local, $seghis);

        usort($combined, static fn (array $a, array $b): int => strcmp(
            $b['dateRegistered'] ?? '',
            $a['dateRegistered'] ?? ''
        ));

        $total = count($combined);
        $offset = ($page - 1) * $perPage;

        Response::json([
            'items' => array_slice($combined, $offset, $perPage),
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => max(1, (int) ceil($total / $perPage)),
            ],
            'sources' => [
                'local' => count($local),
                'seghis' => count($seghis),
            ],
        ], 'Patients retrieved successfully.');
    }

    public function store(Request $request): void
    {
        $input = $request->json();
        foreach (['pid', 'name_first', 'name_last', 'date_birth'] as $field) {
            if (trim((string) ($input[$field] ?? '')) === '') {
                Response::json(null, "{$field} is required.", 422);
            }
        }

        $fields = [
            'pid', 'date_registered', 'name_last', 'name_first', 'name_middle',
            'date_birth', 'age', 'sex', 'civil_status', 'place_birth', 'street1',
            'barangay', 'city', 'province', 'country', 'zip_code', 'ethnic', 'religion',
            'mother_of_patient', 'father_of_patient', 'spouse_of_patient', 'death_date',
            'brgy_code', 'brgy_code_10', 'municity_code', 'municity_code_10',
            'province_code', 'province_code_10', 'region_code', 'region_code_10',
        ];
        $payload = [];
        foreach ($fields as $field) {
            $value = $input[$field] ?? null;
            $payload[$field] = $value === '' || $value === '0000-00-00' ? null : $value;
        }

        if ($this->identityExists($payload['name_first'], $payload['name_last'], $payload['date_birth'])) {
            Response::json(null, 'A patient with the same first name, last name, and date of birth already exists.', 409);
        }

        try {
            $columns = implode(', ', $fields);
            $placeholders = implode(', ', array_map(static fn (string $field): string => ':' . $field, $fields));
            $statement = $this->pdo->prepare("INSERT INTO patients ({$columns}) VALUES ({$placeholders})");
            $statement->execute($payload);
        } catch (\PDOException $exception) {
            if ($exception->getCode() === '23000') {
                Response::json(null, 'A local patient with this PID already exists.', 409);
            }
            throw $exception;
        }

        $id = (int) $this->pdo->lastInsertId();
        $statement = $this->pdo->prepare('SELECT * FROM patients WHERE id = :id');
        $statement->execute(['id' => $id]);

        $patient = $this->mapLocalPatient($statement->fetch() ?: []);
        $notification = $this->pdo->prepare('INSERT INTO notifications (user_id, type, title, message) VALUES (:user_id, :type, :title, :message)');
        $notification->execute([
            'user_id' => $request->user()['id'],
            'type' => 'patient_created',
            'title' => 'Patient added',
            'message' => sprintf('You added %s (PID: %s).', trim($patient['name_first'] . ' ' . $patient['name_last']), $patient['pid']),
        ]);

        Response::json($patient, 'Patient created successfully.', 201);
    }

    public function update(Request $request): void
    {
        $pid = trim((string) ($request->query()['pid'] ?? ''));
        $input = $request->json();
        $existing = $this->findLocalByPid($pid);
        if (!$existing) {
            Response::json(null, 'Only local patient records can be updated.', 404);
        }

        foreach (['name_first', 'name_last', 'date_birth'] as $field) {
            if (trim((string) ($input[$field] ?? '')) === '') {
                Response::json(null, "{$field} is required.", 422);
            }
        }
        if ($this->identityExists((string) $input['name_first'], (string) $input['name_last'], (string) $input['date_birth'], (int) $existing['id'])) {
            Response::json(null, 'Another patient has the same first name, last name, and date of birth.', 409);
        }

        $fields = ['date_registered', 'name_last', 'name_first', 'name_middle', 'date_birth', 'age', 'sex', 'civil_status', 'place_birth', 'street1', 'barangay', 'city', 'province', 'country', 'zip_code', 'ethnic', 'religion', 'mother_of_patient', 'father_of_patient', 'spouse_of_patient', 'death_date', 'brgy_code', 'brgy_code_10', 'municity_code', 'municity_code_10', 'province_code', 'province_code_10', 'region_code', 'region_code_10'];
        $payload = ['id' => $existing['id']];
        foreach ($fields as $field) {
            $value = $input[$field] ?? null;
            $payload[$field] = $value === '' || $value === '0000-00-00' ? null : $value;
        }
        $assignments = implode(', ', array_map(static fn (string $field): string => "{$field} = :{$field}", $fields));
        $statement = $this->pdo->prepare("UPDATE patients SET {$assignments} WHERE id = :id");
        $statement->execute($payload);
        Response::json($this->mapLocalPatient($this->findLocalByPid($pid) ?: []), 'Patient updated successfully.');
    }

    public function destroy(Request $request): void
    {
        $pid = trim((string) ($request->query()['pid'] ?? ''));
        $statement = $this->pdo->prepare('DELETE FROM patients WHERE pid = :pid');
        $statement->execute(['pid' => $pid]);
        if ($statement->rowCount() === 0) {
            Response::json(null, 'Only local patient records can be deleted.', 404);
        }
        Response::json(null, 'Patient deleted successfully.');
    }

    private function localPatients(string $search): array
    {
        $sql = 'SELECT * FROM patients'; $params = [];
        $terms = $this->searchTerms($search);
        if ($terms !== []) {
            $where = [];
            foreach ($terms as $index => $term) {
                $where[] = "(pid LIKE :term{$index} OR name_first LIKE :term{$index} OR name_last LIKE :term{$index} OR date_birth LIKE :term{$index})";
                $params["term{$index}"] = "%{$term}%";
            }
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        return array_map(fn (array $patient): array => $this->mapLocalPatient($patient), $statement->fetchAll());
    }

    private function segHisPatients(string $search): array
    {
        try {
            $result = $this->segHisPatients->execute();
            $items = isset($result['data']) && is_array($result['data']) ? $result['data'] : $result;
            $items = array_map(
                static fn (array $patient): array => $patient + ['source' => 'seghis'],
                array_values(array_filter($items, 'is_array'))
            );
            $terms = $this->searchTerms($search);
            return array_values(array_filter($items, function (array $patient) use ($terms): bool {
                foreach ($terms as $term) {
                    $haystack = implode(' ', [(string) ($patient['pid'] ?? ''), (string) ($patient['name_first'] ?? ''), (string) ($patient['name_last'] ?? ''), (string) ($patient['date_birth'] ?? '')]);
                    if (stripos($haystack, $term) === false) return false;
                }
                return true;
            }));
        } catch (Throwable) {
            return [];
        }
    }

    private function mergePatients(array $local, array $seghis): array
    {
        $patients = [];
        foreach (array_merge($seghis, $local) as $patient) {
            $pid = (string) ($patient['pid'] ?? '');
            if ($pid === '') {
                continue;
            }
            $patients[$pid] = isset($patients[$pid]) ? array_merge($patients[$pid], $patient) : $patient;
        }
        return array_values($patients);
    }

    private function mapLocalPatient(array $patient): array
    {
        return [
            'pid' => $patient['pid'] ?? null, 'dateRegistered' => $patient['date_registered'] ?? null,
            'name_last' => $patient['name_last'] ?? null, 'name_first' => $patient['name_first'] ?? null,
            'name_middle' => $patient['name_middle'] ?? null, 'date_birth' => $patient['date_birth'] ?? null,
            'age' => $patient['age'] ?? null, 'sex' => $patient['sex'] ?? null,
            'civil_status' => $patient['civil_status'] ?? null, 'place_birth' => $patient['place_birth'] ?? null,
            'Street1' => $patient['street1'] ?? null, 'Barangay' => $patient['barangay'] ?? null,
            'City' => $patient['city'] ?? null, 'Province' => $patient['province'] ?? null,
            'Country' => $patient['country'] ?? null, 'ZipCode' => $patient['zip_code'] ?? null,
            'ethnic' => $patient['ethnic'] ?? null, 'religion' => $patient['religion'] ?? null,
            'MotherOfPatient' => $patient['mother_of_patient'] ?? null,
            'FatherOfPatient' => $patient['father_of_patient'] ?? null,
            'SpouseOfPatient' => $patient['spouse_of_patient'] ?? null,
            'deathdate' => $patient['death_date'] ?? null, 'brgy_code' => $patient['brgy_code'] ?? null,
            'brgy_code_10' => $patient['brgy_code_10'] ?? null,
            'municity_code' => $patient['municity_code'] ?? null,
            'municity_code_10' => $patient['municity_code_10'] ?? null,
            'province_code' => $patient['province_code'] ?? null,
            'province_code_10' => $patient['province_code_10'] ?? null,
            'region_code' => $patient['region_code'] ?? null,
            'region_code_10' => $patient['region_code_10'] ?? null,
            'source' => 'local',
        ];
    }

    private function identityExists(?string $first, ?string $last, ?string $birthDate, ?int $excludeId = null): bool
    {
        $sql = 'SELECT id FROM patients WHERE name_first = :first AND name_last = :last AND date_birth = :birth_date';
        $params = ['first' => $first, 'last' => $last, 'birth_date' => $birthDate];
        if ($excludeId !== null) { $sql .= ' AND id != :exclude_id'; $params['exclude_id'] = $excludeId; }
        $statement = $this->pdo->prepare($sql); $statement->execute($params);
        return (bool) $statement->fetch();
    }

    private function findLocalByPid(string $pid): ?array
    {
        $statement = $this->pdo->prepare('SELECT * FROM patients WHERE pid = :pid'); $statement->execute(['pid' => $pid]);
        return $statement->fetch() ?: null;
    }

    private function searchTerms(string $search): array
    {
        return array_values(array_filter(array_map('trim', explode(',', $search)), static fn (string $term): bool => $term !== ''));
    }
}
