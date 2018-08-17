<?php
return [
    'db' => [
        'dsn' => 'mysql:dbname='.$_SERVER['RDS_DB_NAME'].';host=' . $_SERVER['RDS_HOSTNAME'] . ';port=' . $_SERVER['RDS_PORT'],
        'username' => $_SERVER['RDS_USERNAME'],
        'password' => $_SERVER['RDS_PASSWORD'],
        'host' => $_SERVER['RDS_HOSTNAME'],
        'env' => 'production',
        'url' => '',
        'adapters' => [
            'be_db' => [
                'driver' => 'Pdo_Mysql',
                'database' => $_SERVER['RDS_DB_NAME'],
                'username' => $_SERVER['RDS_USERNAME'],
                'password' => $_SERVER['RDS_PASSWORD'],
                'dsn' => 'mysql:host=' . $_SERVER['RDS_HOSTNAME'] . ';dbname=' . $_SERVER['RDS_DB_NAME'] . ';port=' . $_SERVER['RDS_PORT'],
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
                        'dsn' => 'mysql:host='  . $_SERVER['RDS_HOSTNAME'] . ';dbname=' . $_SERVER['RDS_DB_NAME'] . ';port=' . $_SERVER['RDS_PORT'],
                        'route' => '/oauth',
                        'username' => $_SERVER['RDS_USERNAME'],
                        'password' => $_SERVER['RDS_PASSWORD'],
                    ],
                ],
            ],
        ],
    ],
];
