<?php

return [
    'default' => env('LOG_CHANNEL', 'stack'),

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['single'],
            'ignore_exceptions' => false,
        ],
        'error' => [
            'driver' => 'single',
            'path' => storage_path('logs/error.log'),
            'level' => 'info',
        ],
        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/api.log'),
            'level' => 'debug',
        ],
    ],
];
