<?php

declare(strict_types=1);

use App\Framework\Controllers\AuthController;
use App\Framework\Controllers\DepartmentController;
use App\Framework\Controllers\DoctorController;
use App\Framework\Controllers\NurseController;
use App\Framework\Controllers\PatientController;
use App\Framework\Controllers\EncounterController;
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

        '/seghis/doctors/show'=>[
              'middleware'=>[
                AuthMiddleware::class,
                fn()=>new RoleMiddleware(['administrator']),
            ],
            'action'=>[DoctorController::class,'index']
        ],

        '/seghis/nurses/show' => [
            'middleware' => [
                AuthMiddleware::class,
                fn () => new RoleMiddleware(['administrator']),
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
    ],

    'POST' => [
        '/auth/login' => [AuthController::class, 'login'],
        '/auth/refresh' => [AuthController::class, 'refresh'],

        '/auth/logout' => [
            'middleware' => [AuthMiddleware::class],
            'action' => [AuthController::class, 'logout'],
        ],
    ],

    'PUT' => [],

    'PATCH' => [],

    'DELETE' => [],

];