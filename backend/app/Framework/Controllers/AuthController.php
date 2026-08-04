<?php

declare(strict_types=1);

namespace App\Framework\Controllers;

use App\Application\DTOs\LoginRequest;
use App\Application\UseCases\Auth\LoginUseCase;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Shared\Exceptions\AuthenticationException;
use App\Infrastructure\Security\PasswordService;
use PDO;
use Throwable;

final class AuthController
{
    public function __construct(
        private readonly LoginUseCase $loginUseCase,
        private readonly PDO $pdo,
        private readonly PasswordService $passwords
    ) {
    }

    public function register(Request $request): void
    {
        $input = $request->json();
        $pid = trim((string) ($input['pid'] ?? $input['staff_id'] ?? ''));
        $loginId = trim((string) ($input['login_id'] ?? ''));
        $password = (string) ($input['password'] ?? '');

        foreach (['PID' => $pid, 'Login ID' => $loginId, 'Password' => $password] as $field => $value) {
            if ($value === '') {
                Response::json(null, "{$field} is required.", 422);
            }
        }

        if (strlen($password) < 8) {
            Response::json(null, 'Password must contain at least 8 characters.', 422);
        }

        // Doctors are verified by their PID. Nurse records currently use their personnel number as the staff PID.
        $staff = $this->pdo->prepare(
            "SELECT personnel_nr, login_id, name_first, name_last, 'doctor' AS role
             FROM doctors
             WHERE login_id = :login_id AND (pid = :pid OR personnel_nr = :pid)
             UNION ALL
             SELECT personnel_nr, login_id, name_first, name_last, 'nurse' AS role
             FROM nurses
             WHERE login_id = :login_id AND personnel_nr = :pid
             LIMIT 1"
        );
        $staff->execute(['login_id' => $loginId, 'pid' => $pid]);
        $record = $staff->fetch(PDO::FETCH_ASSOC);

        if (!$record) {
            Response::json(null, 'We could not find a doctor or nurse record matching that PID and Login ID.', 404);
        }

        $role = $this->pdo->prepare('SELECT id FROM roles WHERE name = :role');
        $role->execute(['role' => $record['role']]);
        $roleId = $role->fetchColumn();

        if (!$roleId) {
            Response::json(null, 'The matching staff role is not configured.', 500);
        }

        try {
            $user = $this->pdo->prepare(
                'INSERT INTO users (role_id, username, password_hash, first_name, last_name)
                 VALUES (:role_id, :username, :password_hash, :first_name, :last_name)'
            );
            $user->execute([
                'role_id' => $roleId,
                'username' => $record['login_id'],
                'password_hash' => $this->passwords->hash($password),
                'first_name' => $record['name_first'],
                'last_name' => $record['name_last'],
            ]);
        } catch (\PDOException $exception) {
            if ($exception->getCode() === '23000') {
                Response::json(null, 'An account already exists for this Login ID.', 409);
            }

            throw $exception;
        }

        Response::json([
            'username' => $record['login_id'],
            'role' => $record['role'],
        ], 'Account created successfully.', 201);
    }

    public function login(Request $request): void
    {
        try {
            $dto = new LoginRequest(
            username: trim($request->input('username')),
            password: $request->input('password')
        );

            $result = $this->loginUseCase->execute($dto);

            Response::json(
                [
                    'token' => $result->token,
                    'expires_in' => $result->expiresIn,
                    'user' => $result->user,
                ],
                'Login successful.'
            );
        } catch (AuthenticationException $e) {
            Response::json(null, $e->getMessage(), 401);
        } catch (Throwable $e) {
            Response::json(null, $e->getMessage(), 500);
        }
    }

    public function me(Request $request): void
{
    Response::json(
        $request->user(),
        'Authenticated user.'
    );
}

    public function logout(): void
    {
        Response::json(
            null,
            'Logout successful.'
        );
    }

    public function refresh(): void
    {
        Response::json(
            null,
            'Token refreshed.'
        );
    }
}
