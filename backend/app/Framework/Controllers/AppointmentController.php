<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Framework\Http\Request;
use App\Framework\Http\Response;
use PDO;

final class AppointmentController
{
    private const FIELDS = [
        'patient_pid', 'patient_name', 'patient_birth_date', 'appointment_at',
        'deptid', 'department_name', 'doctor_personnel_nr', 'doctor_name',
        'doctor_login_id', 'nurse_personnel_nr', 'nurse_name', 'nurse_login_id', 'notes',
    ];

    public function __construct(private readonly PDO $pdo)
    {
    }

    public function index(Request $request): void
    {
        $user = $request->user();
        if (($user['role'] ?? '') === 'administrator') {
            $appointments = $this->pdo->query('SELECT * FROM appointments ORDER BY appointment_at DESC')->fetchAll();
        } else {
            $statement = $this->pdo->prepare('SELECT * FROM appointments WHERE doctor_login_id = :username OR nurse_login_id = :username ORDER BY appointment_at DESC');
            $statement->execute(['username' => $user['username']]);
            $appointments = $statement->fetchAll();
        }
        Response::json($appointments, 'Appointments retrieved successfully.');
    }

    public function patients(Request $request): void
    {
        $search = trim((string) ($request->query()['search'] ?? ''));
        if ($search === '') {
            Response::json([], 'Patients retrieved successfully.');
        }

        $statement = $this->pdo->prepare('SELECT pid, name_first, name_last, date_birth FROM patients WHERE pid LIKE :search OR name_first LIKE :search OR name_last LIKE :search OR date_birth LIKE :search ORDER BY name_last LIMIT 20');
        $statement->execute(['search' => "%{$search}%"]);
        Response::json($statement->fetchAll(), 'Patients retrieved successfully.');
    }

    public function store(Request $request): void
    {
        $input = $request->json();
        $this->validate($input);
        $this->ensureNoOtherActiveAppointment((string) $input['patient_pid']);

        $payload = $this->payload($input);
        $payload['created_by'] = $request->user()['id'];
        $columns = [...self::FIELDS, 'created_by'];
        $statement = $this->pdo->prepare('INSERT INTO appointments (' . implode(', ', $columns) . ') VALUES (' . implode(', ', array_map(static fn (string $field): string => ':' . $field, $columns)) . ')');
        $statement->execute($payload);

        $this->notifyParticipants($payload, 'appointment_assigned', 'Appointment assigned');
        Response::json(['id' => (int) $this->pdo->lastInsertId()], 'Appointment created successfully.', 201);
    }

    public function update(Request $request): void
    {
        $id = (int) ($request->query()['id'] ?? 0);
        $existing = $this->find($id);
        if (!$existing) {
            Response::json(null, 'Appointment not found.', 404);
        }
        $this->ensureCanManage($request, $existing);

        $input = $request->json();
        $merged = array_merge($existing, $input);
        $this->validate($merged);
        if (!in_array($existing['status'], ['resolved', 'cancelled'], true)) {
            $this->ensureNoOtherActiveAppointment((string) $merged['patient_pid'], $id);
        }

        $payload = $this->payload($merged);
        $payload['id'] = $id;
        $assignments = implode(', ', array_map(static fn (string $field): string => "{$field} = :{$field}", self::FIELDS));
        $statement = $this->pdo->prepare("UPDATE appointments SET {$assignments} WHERE id = :id");
        $statement->execute($payload);

        $payload['created_by'] = $existing['created_by'];
        $this->notifyParticipants($payload, 'appointment_updated', 'Appointment updated');
        Response::json(['id' => $id], 'Appointment updated successfully.');
    }

    public function updateStatus(Request $request): void
    {
        $id = (int) ($request->query()['id'] ?? 0);
        $existing = $this->find($id);
        if (!$existing) {
            Response::json(null, 'Appointment not found.', 404);
        }
        $this->ensureCanManage($request, $existing);
        $status = (string) ($request->json()['status'] ?? '');
        if (!in_array($status, ['resolved', 'cancelled'], true)) {
            Response::json(null, 'Status must be resolved or cancelled.', 422);
        }

        $statement = $this->pdo->prepare('UPDATE appointments SET status = :status WHERE id = :id');
        $statement->execute(['status' => $status, 'id' => $id]);
        Response::json(['id' => $id, 'status' => $status], 'Appointment status updated.');
    }

    public function destroy(Request $request): void
    {
        $id = (int) ($request->query()['id'] ?? 0);
        $existing = $this->find($id);
        if (!$existing) {
            Response::json(null, 'Appointment not found.', 404);
        }
        $this->ensureCanManage($request, $existing);
        $statement = $this->pdo->prepare('DELETE FROM appointments WHERE id = :id');
        $statement->execute(['id' => $id]);
        Response::json(null, 'Appointment deleted successfully.');
    }

    private function validate(array $input): void
    {
        foreach (['patient_pid', 'patient_name', 'appointment_at'] as $field) {
            if (trim((string) ($input[$field] ?? '')) === '') {
                Response::json(null, "{$field} is required.", 422);
            }
        }
    }

    private function payload(array $input): array
    {
        $payload = [];
        foreach (self::FIELDS as $field) {
            $value = $input[$field] ?? null;
            $payload[$field] = $value === '' ? null : $value;
        }
        return $payload;
    }

    private function find(int $id): array|false
    {
        $statement = $this->pdo->prepare('SELECT * FROM appointments WHERE id = :id');
        $statement->execute(['id' => $id]);
        return $statement->fetch();
    }

    private function ensureNoOtherActiveAppointment(string $patientPid, ?int $exceptId = null): void
    {
        $sql = "SELECT id FROM appointments WHERE patient_pid = :pid AND status NOT IN ('resolved', 'cancelled')";
        $params = ['pid' => $patientPid];
        if ($exceptId) {
            $sql .= ' AND id != :id';
            $params['id'] = $exceptId;
        }
        $sql .= ' LIMIT 1';
        $statement = $this->pdo->prepare($sql);
        $statement->execute($params);
        if ($statement->fetch()) {
            Response::json(null, 'This patient already has an active appointment. Resolve or cancel it before booking another one.', 409);
        }
    }

    private function ensureCanManage(Request $request, array $appointment): void
    {
        $user = $request->user();
        if (($user['role'] ?? '') === 'administrator') {
            return;
        }

        $username = (string) ($user['username'] ?? '');
        if ($username === '' || !in_array($username, [(string) ($appointment['doctor_login_id'] ?? ''), (string) ($appointment['nurse_login_id'] ?? '')], true)) {
            Response::json(null, 'You can only modify appointments assigned to you.', 403);
        }
    }

    private function notifyParticipants(array $appointment, string $type, string $title): void
    {
        $userIds = [(int) $appointment['created_by']];
        foreach (['doctor_login_id', 'nurse_login_id'] as $field) {
            if (!$appointment[$field]) {
                continue;
            }
            $statement = $this->pdo->prepare('SELECT id FROM users WHERE username = :username AND is_active = TRUE AND deleted_at IS NULL');
            $statement->execute(['username' => $appointment[$field]]);
            if ($id = $statement->fetchColumn()) {
                $userIds[] = (int) $id;
            }
        }

        $message = sprintf('You are assigned to %s at %s (%s).', $appointment['patient_name'], $appointment['appointment_at'], $appointment['department_name'] ?? 'No department');
        $statement = $this->pdo->prepare('INSERT INTO notifications (user_id, type, title, message) VALUES (:user_id, :type, :title, :message)');
        foreach (array_unique($userIds) as $userId) {
            $statement->execute(['user_id' => $userId, 'type' => $type, 'title' => $title, 'message' => $message]);
        }
    }
}
