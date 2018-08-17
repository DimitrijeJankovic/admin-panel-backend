<?php
return [
    'db' => [
        'dsn' => 'mysql:dbname=admin_panel;host=localhost',
        'username' => 'root',
        'password' => 'fubar.909',
        'host' => 'localhost',
        'env' => 'development',
        'url' => 'http://admin-panel.com:450',
        'adapters' => [
            'admin_panel' => [
                'driver' => 'Pdo_Mysql',
                'database' => 'admin_panel',
                'username' => 'root',
                'password' => 'fubar.909',
                'dsn' => 'mysql:host=localhost;dbname=admin_panel',
                'driver_options' => [
                    1002 => 'SET NAMES \'UTF8\'',
                ],
            ],
        ],
    ],
    'statuslib' => [
        'array_mapper_path' => 'data/statuslib.php',
    ],
    'zf-oauth2' => [
        'allow_implicit' => true,
        'options' => [
            'always_issue_new_refresh_token' => true,
        ],
        'access_lifetime' => 2592000,
    ],
    'view_manager' => [
        'template_map' => [
            'error/403' => 'module/Application/view/error/403.phtml',
        ],
    ],
    'zf-mvc-auth' => [
        'authentication' => [
            'adapters' => [
                'oauth' => [
                    'adapter' => \ZF\MvcAuth\Authentication\OAuth2Adapter::class,
                    'storage' => [
                        'adapter' => \pdo::class,
                        'dsn' => 'mysql:host=localhost;dbname=admin_panel',
                        'route' => '/oauth',
                        'username' => 'root',
                        'password' => 'fubar.909',
                    ],
                ],
            ],
        ],
    ],
];
