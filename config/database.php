<?php

return [
    
    'default' => env('DB_CONNECTION', 'mysql'),

    'connections' => [
         'mysql' => [
             'driver'    => 'mysql',
             'host'      => env('DB_HOST'),
             'port'      => env('DB_PORT'),
             'database'  => env('DB_DATABASE'),
             'username'  => env('DB_USERNAME'),
             'password'  => env('DB_PASSWORD'),
             'charset'   => 'utf8',
             'collation' => 'utf8_unicode_ci',
             'prefix'    => '',
             'strict'    => false,
          ],
        'rrhh' => [
            'driver'        => 'oracle',
            'tns'           => env('DB_TNS', 'PRUEBAS.LOCAL'),
            // 'service_name' => 'XE',
            'host'          => env('DB_HOST', 'localhost'),
            'port'          => env('DB_PORT', '1521'),
            'database'      => env('DB_DATABASE', 'xe'),
            'username'      => env('DB_USERNAME2', 'rrhh'),
            'password'      => env('DB_PASSWORD2', 'rrhhadmin'),
            'charset'       => env('DB_CHARSET', 'AL32UTF8'),
            'prefix'        => env('DB_PREFIX', ''),
            'prefix_schema' => env('DB_SCHEMA_PREFIX', ''),
            // 'edition'       => env('DB_EDITION', 'ora$base'),
         ],
     ],
 ];