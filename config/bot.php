<?php
// config/bot.php

return [
    /*
    |----------------------------------------------------------
    | Token rahasia untuk autentikasi bot Python ke Laravel
    | Simpan nilainya di .env sebagai BOT_TOKEN=xxx
    |----------------------------------------------------------
    */
    'token' => env('BOT_TOKEN', null),
];