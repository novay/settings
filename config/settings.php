<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Encryption Settings
    |--------------------------------------------------------------------------
    |
    | Aktifkan encryption untuk SEMUA setting. Menggunakan novay/kunci (recommended)
    | atau fallback ke Laravel Crypt.
    |
    */
    'encrypt' => env('SETTINGS_ENCRYPT', false),

    /*
    |--------------------------------------------------------------------------
    | Encryption Driver
    |--------------------------------------------------------------------------
    |
    | 'kunci'   => Novay\Kunci (pluggable, support file/KMS)
    | 'laravel' => Illuminate\Support\Facades\Crypt
    |
    */
    'driver' => env('SETTINGS_ENCRYPTION_DRIVER', 'kunci'),
];
