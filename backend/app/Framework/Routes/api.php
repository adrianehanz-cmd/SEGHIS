<?php

declare(strict_types=1);

use App\Framework\Controllers\AuthController;
use App\Framework\Controllers\AppointmentController;
use App\Framework\Controllers\AddressController;
use App\Framework\Controllers\NotificationController;
use App\Framework\Controllers\DepartmentController;
use App\Framework\Controllers\DoctorController;
use App\Framework\Controllers\DoctorRecordsController;
use App\Framework\Controllers\NurseController;
use App\Framework\Controllers\NurseRecordsController;
use App\Framework\Controllers\PatientController;
use App\Framework\Controllers\PatientRecordsController;
use App\Framework\Controllers\MedicalRecordsController;
use App\Framework\Controllers\EncounterController;
use App\Framework\Controllers\LaboratoryController;
use App\Framework\Controllers\MiscellaneousController;
use App\Framework\Controllers\PharmacyController;
use App\Framework\Controllers\PrescriptionController;
use App\Framework\Controllers\RadiologyController;
use App\Framework\Controllers\WardController;
use App\Framework\Http\Request;
use App\Framework\Http\Response;
use App\Framework\Middlewares\AuthMiddleware;
use App\Framework\Middlewares\RoleMiddleware;

return [

    'GET' => [

        '/' => function (Request $request): void {
            Response::json(
                [
                    'application' => $_ENV['APP_NAME'],
                    'status' => 'Running',
                    'version' => '1.0.0',
                    'environment' => $_ENV['APP_ENV'],
                ],
                'API is running.'
            );
        },

        '/health' => function (Request $request): void {
            Response::json(
                [
                    'status' => 'healthy',
                    'timestamp' => date('c'),
                ],
                'System is healthy.'
            );
        },

        '/auth/me' => [
            'middleware' => [AuthMiddleware::class],
            'action' => [AuthController::class, 'me'],
        ],

        /*
        |--------------------------------------------------------------------------
        | SegHIS (protected)
        |--------------------------------------------------------------------------
        */

        '/seghis/departments/show' => [
            'middleware' => [AuthMiddleware::class],
            'action' => [DepartmentController::class, 'index'],
        ],

        '/seghis/patients/show' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse']),
            ],
            'action' => [PatientController::class, 'index'],
        ],

        '/patients' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse']),
            ],
            'action' => [PatientRecordsController::class, 'index'],
        ],
        '/appointments' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse'])], 'action' => [AppointmentController::class, 'index']],
        '/appointments/patients' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse'])], 'action' => [AppointmentController::class, 'patients']],
        '/medical-records' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse'])], 'action' => [MedicalRecordsController::class, 'index']],

        '/doctors' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse']),
            ],
            'action' => [DoctorRecordsController::class, 'index'],
        ],

        '/nurses' => [
            'middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse'])],
            'action' => [NurseRecordsController::class, 'index'],
        ],

        '/locations/regions' => [
            'middleware' => [AuthMiddleware::class],
            'action' => [AddressController::class, 'regions'],
        ],
        '/locations/provinces' => [
            'middleware' => [AuthMiddleware::class],
            'action' => [AddressController::class, 'provinces'],
        ],
        '/locations/municipalities' => [
            'middleware' => [AuthMiddleware::class],
            'action' => [AddressController::class, 'municipalities'],
        ],
        '/locations/barangays' => [
            'middleware' => [AuthMiddleware::class],
            'action' => [AddressController::class, 'barangays'],
        ],
        '/notifications' => [
            'middleware' => [AuthMiddleware::class],
            'action' => [NotificationController::class, 'index'],
        ],

        '/seghis/doctors/show'=>[
              'middleware'=>[
                AuthMiddleware::class,
                fn()=>new RoleMiddleware(['administrator', 'doctor', 'nurse']),
            ],
            'action'=>[DoctorController::class,'index']
        ],

        '/seghis/nurses/show' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse']),
            ],
            'action' => [NurseController::class, 'index'],
        ],

        '/seghis/encounter/show' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator','nurse']),
            ],
            'action' => [EncounterController::class, 'index'],
        ],

        '/seghis/laboratory/show' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator','nurse','doctor']),
            ],
            'action' => [LaboratoryController::class, 'index'],
        ],

        '/seghis/miscellaneous/show' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator','nurse','doctor']),
            ],
            'action' => [MiscellaneousController::class, 'index'],
        ],

        '/seghis/pharmacy/show' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator','nurse','doctor']),
            ],
            'action' => [PharmacyController::class, 'index'],
        ],

        '/seghis/radiology/show' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator','nurse','doctor']),
            ],
            'action' => [RadiologyController::class, 'index'],
        ],

        '/seghis/prescription/show' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator','nurse','doctor']),
            ],
            'action' => [PrescriptionController::class, 'index'],
        ],

        '/seghis/ward/show' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator','nurse','doctor']),
            ],
            'action' => [WardController::class, 'index'],
        ],
    ],

    'POST' => [
        '/auth/login' => [AuthController::class, 'login'],
        '/auth/register' => [AuthController::class, 'register'],
        '/auth/refresh' => [AuthController::class, 'refresh'],

        '/auth/logout' => [
            'middleware' => [AuthMiddleware::class],
            'action' => [AuthController::class, 'logout'],
        ],
        '/appointments' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse'])], 'action' => [AppointmentController::class, 'store']],
        '/doctors' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator'])], 'action' => [DoctorRecordsController::class, 'index']],
        '/patients' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse']),
            ],
            'action' => [PatientRecordsController::class, 'store'],
        ],
        '/doctors' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator'])], 'action' => [DoctorRecordsController::class, 'store']],
        '/nurses' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator'])], 'action' => [NurseRecordsController::class, 'store']],
    ],

    'PUT' => [],

    'PATCH' => [
        '/appointments' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse'])], 'action' => [AppointmentController::class, 'update']],
        '/appointments/status' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse'])], 'action' => [AppointmentController::class, 'updateStatus']],
        '/patients' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse'])], 'action' => [PatientRecordsController::class, 'update']],
        '/notifications/read' => ['middleware' => [AuthMiddleware::class], 'action' => [NotificationController::class, 'markAllRead']],
    ],

    'DELETE' => [
        '/appointments' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse'])], 'action' => [AppointmentController::class, 'destroy']],
        '/patients' => ['middleware' => [AuthMiddleware::class, fn () => new RoleMiddleware(['administrator', 'doctor', 'nurse'])], 'action' => [PatientRecordsController::class, 'destroy']],
    ],

];
