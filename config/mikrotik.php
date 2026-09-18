<?php
return [
    'host'     => env('MIKROTIK_HOST', '103.182.235.42'),
    'port'     => env('MIKROTIK_PORT', 2112),
    'username' => env('MIKROTIK_USER', 'admin'),
    'password' => env('MIKROTIK_PASS', 'm41k3l'),
    'webhook'  => env('BOT_WEBHOOK_URL', ''),
    'bot_url'  => env('BOT_URL', 'http://170.1.0.9:5000'),
    'bot_token' => env('BOT_TOKEN', ''),
];