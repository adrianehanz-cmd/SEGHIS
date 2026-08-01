<?php

return [
    'path' => dirname(__DIR__, 3) . '/storage/logs',

    'channels' => [
        'application' => 'application.log',
        'api' => 'api.log',
        'error' => 'error.log',
        'audit' => 'audit.log',
    ],
];