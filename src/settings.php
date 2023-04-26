<?php
return [
    // Renderer settings
    'renderer' => [
        'template_path' => __DIR__ . '/../templates/',
    ],

    // Monolog settings
    'logger' => [
        'name' => 'slim-app',
        'path' => __DIR__ . '/../logs/app.log',
    ],

    // Database settings
    'database' => [
        'driver' => getenv('DB_DRIVER'),
        'host' => getenv('DB_HOST'),
        'user' => getenv('DB_USER'),
        'pass' => getenv('DB_PASS'),
        'base' => getenv('DB_BASE')
    ],
];
