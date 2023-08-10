<?php

require_once __DIR__ . '/vendor/autoload.php';
use Dotenv\Dotenv;
Dotenv::createImmutable(base_path())->load();

return
    [
        'paths' => [
            'migrations' => '%%PHINX_CONFIG_DIR%%/database/migrations',
            'seeds' => '%%PHINX_CONFIG_DIR%%/database/seeds'
        ],
        'environments' => [
            'default_migration_table' => 'phinxlog',
            'default_environment' => 'development',
            'production' => [
                'adapter' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'name' => env('DB_DATABASE', 'forge'),
                'user' => env('DB_USERNAME', 'forge'),
                'pass' => env('DB_PASSWORD', ''),
                'port' => env('DB_PORT', '3306'),
                'charset' => 'utf8mb4',
            ],
            'development' => [
                'adapter' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'name' => env('DB_DATABASE', 'forge'),
                'user' => env('DB_USERNAME', 'forge'),
                'pass' => env('DB_PASSWORD', ''),
                'port' => env('DB_PORT', '3306'),
                'charset' => 'utf8mb4',
            ],
            'testing' => [
                'adapter' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'name' => env('DB_DATABASE', 'forge'),
                'user' => env('DB_USERNAME', 'forge'),
                'pass' => env('DB_PASSWORD', ''),
                'port' => env('DB_PORT', '3306'),
                'charset' => 'utf8mb4',
            ]
        ],
        'version_order' => 'creation'
    ];
