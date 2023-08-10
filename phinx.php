<?php

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
                'host' => getenv('DB_HOST', '127.0.0.1'),
                'name' => getenv('DB_DATABASE', 'forge'),
                'user' => getenv('DB_USERNAME', 'forge'),
                'pass' => getenv('DB_PASSWORD', ''),
                'port' => getenv('DB_PORT', '3306'),
                'charset' => 'utf8',
            ],
            'development' => [
                'adapter' => 'mysql',
                'host' => 'localhost',
                'name' => 'development_db',
                'user' => 'root',
                'pass' => '',
                'port' => '3306',
                'charset' => 'utf8',
            ],
            'testing' => [
                'adapter' => 'mysql',
                'host' => 'localhost',
                'name' => 'testing_db',
                'user' => 'root',
                'pass' => '',
                'port' => '3306',
                'charset' => 'utf8',
            ]
        ],
        'version_order' => 'creation'
    ];
