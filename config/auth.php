<?php

// config/auth.php

return [
    'defaults' => [
        'guard' => 'web',
        'passwords' => 'logins',
    ],

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'logins',
        ],
    ],

    'providers' => [
        'logins' => [
            'driver' => 'eloquent',
            'model' => App\Models\Login::class,
        ],
    ],

    'passwords' => [
        'logins' => [
            'provider' => 'logins',
            'table' => 'password_reset', // Ensure this matches your table name
            'expire' => 60,
            'throttle' => 60,
        ],
    ],
];
