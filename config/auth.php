<?php

return [

    'defaults' => [
        'guard'     => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        // Guard default lab_management — TIDAK DIUBAH
        'web' => [
            'driver'   => 'session',
            'provider' => 'users',
        ],

        // Guard baru khusus finance
        'finance' => [
            'driver'   => 'session',
            'provider' => 'finance_users',
        ],
    ],

    'providers' => [
        // Provider default lab_management — TIDAK DIUBAH
        'users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\User::class,
        ],

        // Provider baru untuk finance
        'finance_users' => [
            'driver' => 'eloquent',
            'model'  => App\Models\Finance\FinanceUser::class,
        ],
    ],

    'passwords' => [
        // Reset password default — TIDAK DIUBAH
        'users' => [
            'provider' => 'users',
            'table'    => 'password_reset_tokens',
            'expire'   => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,

];