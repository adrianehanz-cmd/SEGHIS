# SegHIS API and Application Logic

SegHIS is a role-based hospital workspace for administrators, doctors, and nurses. The frontend is a React application and the API is a PHP service backed by MySQL and the SegHIS data source.

## API basics

The API is served from the backend `public` directory. Set the frontend `VITE_API_URL` to that URL, for example:

```text
http://localhost/SegHIS/backend/public
```

All API responses use this envelope:

```json
{
  "success": true,
  "message": "Human-readable result",
  "data": {}
}
```

Protected endpoints require:

```http
Authorization: Bearer <token>
```

## Authentication and account activation

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `POST` | `/auth/register` | Activate a doctor or nurse account. |
| `POST` | `/auth/login` | Sign in and receive a token plus user details. |
| `GET` | `/auth/me` | Return the authenticated user. |
| `POST` | `/auth/logout` | End the current authenticated session. |
| `POST` | `/auth/refresh` | Refresh endpoint placeholder. |

### Register a staff account

```json
POST /auth/register
{
  "pid": "DOC-PID-001",
  "login_id": "m.santos",
  "password": "at-least-8-characters"
}
```

Registration logic:

1. The API requires a PID/personnel number, Login ID, and a password of at least eight characters.
2. It checks the doctor registry using the supplied `pid` and `login_id` (the doctor personnel number is also accepted for compatibility).
3. It checks the nurse registry using `personnel_nr` and `login_id`. Nurses do **not** use a PID.
4. The matching registry determines the role: `doctor` or `nurse`.
5. A `users` record is inserted with the registry Login ID, staff name, selected role, and a bcrypt password hash.
6. A Login ID can only have one user account.

### Sign in

```json
POST /auth/login
{
  "username": "m.santos",
  "password": "at-least-8-characters"
}
```

The successful response contains a JWT and a user object with `id`, `username`, `name`, `role_id`, and `role`. The frontend stores these and shows the role-specific dashboard and sidebar.

## Roles and navigation

| Role | Sidebar | Dashboard focus |
| --- | --- | --- |
| Administrator | Overview, Patients, Appointments, Departments, Doctors, Nurses | Hospital-wide overview. |
| Doctor | Overview, Encounter, Appointments | Assigned schedule, active care tasks, and updates. |
| Nurse | Overview, Patients, Encounter, Appointments | Assigned schedule, patient coordination, and updates. |

Doctors and nurses may use the same Patients and Appointments pages as administrators. The API permits all three roles to read, create, update, and delete local patient records, and to create or update appointments. Only administrators may add doctor or nurse registry records.

## Core API endpoints

### Patients

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `GET` | `/patients?page=1&per_page=10&search=` | List local and SegHIS patients. |
| `POST` | `/patients` | Create a local patient. |
| `PATCH` | `/patients?pid={pid}` | Update a local patient. |
| `DELETE` | `/patients?pid={pid}` | Delete a local patient. |
| `GET` | `/seghis/patients/show` | Read patient records directly from SegHIS. |

Patient rules:

- Creating a patient requires `pid`, `name_first`, `name_last`, and `date_birth`.
- A duplicate first name, last name, and birth date is rejected.
- SegHIS-source records are read-only; only local records can be edited or deleted.
- Creating a local patient creates a notification for the acting user.

### Appointments

| Method | Endpoint | Purpose |
| --- | --- | --- |
| `GET` | `/appointments` | List appointments. |
| `GET` | `/appointments/patients?search=` | Find patients while creating an appointment. |
| `POST` | `/appointments` | Create an appointment. |
| `PATCH` | `/appointments/status?id={id}` | Mark an appointment `resolved` or `cancelled`. |

Appointment rules:

- Creation requires `patient_pid`, `patient_name`, and `appointment_at`.
- A patient cannot have more than one active appointment at a time.
- `resolved` and `cancelled` appointments are not active.
- The creator and any assigned doctor or nurse with an active user account receive a notification.
- The doctor and nurse dashboards show appointments assigned to the signed-in Login ID.

### Staff, lookup, and notifications

| Method | Endpoint | Access | Purpose |
| --- | --- | --- | --- |
| `GET` | `/doctors` | All staff roles | List doctor records. |
| `POST` | `/doctors` | Administrator | Add a local doctor registry record. |
| `GET` | `/nurses` | All staff roles | List nurse records. |
| `POST` | `/nurses` | Administrator | Add a local nurse registry record. |
| `GET` | `/seghis/departments/show` | Signed in | Department lookup. |
| `GET` | `/locations/regions` | Signed in | Region lookup. |
| `GET` | `/locations/provinces` | Signed in | Province lookup. |
| `GET` | `/locations/municipalities` | Signed in | Municipality lookup. |
| `GET` | `/locations/barangays` | Signed in | Barangay lookup. |
| `GET` | `/notifications` | Signed in | Current user notifications. |
| `PATCH` | `/notifications/read` | Signed in | Mark all current user notifications as read. |

The SegHIS clinical lookups are available under `/seghis/*/show`, including doctors, nurses, encounter, laboratory, pharmacy, radiology, prescription, ward, and related records. Encounter access is restricted to administrators and nurses.

## Database setup

Run migrations from the backend directory:

```bash
php database/migrate.php
```

The migrations create the roles, users, local patient registry, doctor and nurse registries, appointments, notifications, and supporting tables. Configure database credentials and the JWT secret in the backend `.env` file before starting the API.

## Security notes

- Passwords are never saved in plain text; user passwords use bcrypt hashes.
- JWTs are required for protected routes.
- Middleware validates the token and loads the current user before role checks run.
- Role checks are enforced by the backend. Sidebar visibility is a convenience, not the security boundary.
