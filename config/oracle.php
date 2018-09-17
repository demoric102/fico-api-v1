<?php

return [
    'oracle' => [
        'driver'         => 'oracle',
        'tns'            => env('DB_TNS', ''),
        'host'           => env('DB_HOST', '172.16.1.11'),
        'port'           => env('DB_PORT', '1521'),
        'database'       => env('DB_DATABASE', 'sb2prod'),
        'service_name' => env('SERVICE_NAME','sb2prod'),
        'username'       => env('DB_USERNAME', 'sbreportmart'),
        'password'       => env('DB_PASSWORD', 'sbreportmart'),
        'charset'        => env('DB_CHARSET', 'AL32UTF8'),
        'prefix'         => env('DB_PREFIX', ''),
        'prefix_schema'  => env('DB_SCHEMA_PREFIX', ''),
        'server_version' => env('DB_SERVER_VERSION', '11g'),
    ],
];
