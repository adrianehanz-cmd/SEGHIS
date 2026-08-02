<?php

declare(strict_types=1);

use App\Framework\Controllers\Auth\AuthController;

return [

    [
        'POST',
        '/api/login',
        [AuthController::class, 'login']
    ],

];