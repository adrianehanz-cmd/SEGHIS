<?php

declare(strict_types=1);

use App\Framework\Controllers\DepartmentController;
use App\Framework\Controllers\DoctorController;
use App\Framework\Controllers\NurseController;
use App\Framework\Controllers\PatientController;
use App\Framework\Http\Request;
use App\Framework\Http\Response;

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

        /*
        |--------------------------------------------------------------------------
        | SegHIS
        |--------------------------------------------------------------------------
        */

        '/seghis/departments' => [
            DepartmentController::class,
            'index',
        ],

        '/seghis/patients' => [
            PatientController::class,
            'index',
        ],

        '/seghis/doctors' => [
            DoctorController::class,
            'index',
        ],

        '/seghis/nurses' => [
            NurseController::class,
            'index',
        ],
    ],

    'POST' => [

    ],

    'PUT' => [

    ],

    'PATCH' => [

    ],

    'DELETE' => [

    ],

];