<?php



return [

    'name' => env('APP_NAME', 'FilamentApp'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'https://stroop.com'),

    'timezone' => 'Asia/Colombo',

    'locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => 'en',

    'faker_locale' => 'en_US',

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'previous_keys' => [
        ...array_filter(explode(',', env('APP_PREVIOUS_KEYS', '')))
    ],


'aliases' => [
    // ...
    'QrCode' => SimpleSoftwareIO\QrCode\Facades\QrCode::class,
],

  

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

];
