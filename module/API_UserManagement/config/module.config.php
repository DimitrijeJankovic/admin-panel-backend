<?php
return [
    'service_manager' => [
        'factories' => [
            \API_UserManagement\V1\Rest\User\UserResource::class => \API_UserManagement\V1\Rest\User\UserResourceFactory::class,
            \API_UserManagement\V1\Rest\ForgotPassword\ForgotPasswordResource::class => \API_UserManagement\V1\Rest\ForgotPassword\ForgotPasswordResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'api_user-management.rest.user' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/user[/:user_id]',
                    'defaults' => [
                        'controller' => 'API_UserManagement\\V1\\Rest\\User\\Controller',
                    ],
                ],
            ],
            'api_user-management.rest.forgot-password' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/forgot-password[/:forgot_password_id]',
                    'defaults' => [
                        'controller' => 'API_UserManagement\\V1\\Rest\\ForgotPassword\\Controller',
                    ],
                ],
            ],
            'api_user-management.rpc.valid-token' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/valid-token',
                    'defaults' => [
                        'controller' => 'API_UserManagement\\V1\\Rpc\\ValidToken\\Controller',
                        'action' => 'validToken',
                    ],
                ],
            ],
            'api_user-management.rpc.welcome-code' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/welcome-code',
                    'defaults' => [
                        'controller' => 'API_UserManagement\\V1\\Rpc\\WelcomeCode\\Controller',
                        'action' => 'welcomeCode',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            0 => 'api_user-management.rest.user',
            1 => 'api_user-management.rest.forgot-password',
            2 => 'api_user-management.rpc.valid-token',
            3 => 'api_user-management.rpc.welcome-code',
        ],
    ],
    'zf-rest' => [
        'API_UserManagement\\V1\\Rest\\User\\Controller' => [
            'listener' => \API_UserManagement\V1\Rest\User\UserResource::class,
            'route_name' => 'api_user-management.rest.user',
            'route_identifier_name' => 'user_id',
            'collection_name' => 'user',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'DELETE',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_UserManagement\V1\Rest\User\UserEntity::class,
            'collection_class' => \API_UserManagement\V1\Rest\User\UserCollection::class,
            'service_name' => 'User',
        ],
        'API_UserManagement\\V1\\Rest\\ForgotPassword\\Controller' => [
            'listener' => \API_UserManagement\V1\Rest\ForgotPassword\ForgotPasswordResource::class,
            'route_name' => 'api_user-management.rest.forgot-password',
            'route_identifier_name' => 'forgot_password_id',
            'collection_name' => 'forgot_password',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'POST',
                1 => 'PUT',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_UserManagement\V1\Rest\ForgotPassword\ForgotPasswordEntity::class,
            'collection_class' => \API_UserManagement\V1\Rest\ForgotPassword\ForgotPasswordCollection::class,
            'service_name' => 'ForgotPassword',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'API_UserManagement\\V1\\Rest\\User\\Controller' => 'Json',
            'API_UserManagement\\V1\\Rest\\ForgotPassword\\Controller' => 'Json',
            'API_UserManagement\\V1\\Rpc\\ValidToken\\Controller' => 'Json',
            'API_UserManagement\\V1\\Rpc\\WelcomeCode\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'API_UserManagement\\V1\\Rest\\User\\Controller' => [
                0 => 'application/vnd.api_user-management.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_UserManagement\\V1\\Rest\\ForgotPassword\\Controller' => [
                0 => 'application/vnd.api_user-management.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_UserManagement\\V1\\Rpc\\ValidToken\\Controller' => [
                0 => 'application/vnd.api_user-management.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
            'API_UserManagement\\V1\\Rpc\\WelcomeCode\\Controller' => [
                0 => 'application/vnd.api_user-management.v1+json',
                1 => 'application/json',
                2 => 'application/*+json',
            ],
        ],
        'content_type_whitelist' => [
            'API_UserManagement\\V1\\Rest\\User\\Controller' => [
                0 => 'application/vnd.api_user-management.v1+json',
                1 => 'application/json',
            ],
            'API_UserManagement\\V1\\Rest\\ForgotPassword\\Controller' => [
                0 => 'application/vnd.api_user-management.v1+json',
                1 => 'application/json',
            ],
            'API_UserManagement\\V1\\Rpc\\ValidToken\\Controller' => [
                0 => 'application/vnd.api_user-management.v1+json',
                1 => 'application/json',
            ],
            'API_UserManagement\\V1\\Rpc\\WelcomeCode\\Controller' => [
                0 => 'application/vnd.api_user-management.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \API_UserManagement\V1\Rest\User\UserEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_user-management.rest.user',
                'route_identifier_name' => 'user_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_UserManagement\V1\Rest\User\UserCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_user-management.rest.user',
                'route_identifier_name' => 'user_id',
                'is_collection' => true,
            ],
            \API_UserManagement\V1\Rest\ForgotPassword\ForgotPasswordEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_user-management.rest.forgot-password',
                'route_identifier_name' => 'forgot_password_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_UserManagement\V1\Rest\ForgotPassword\ForgotPasswordCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_user-management.rest.forgot-password',
                'route_identifier_name' => 'forgot_password_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-mvc-auth' => [
        'authorization' => [
            'API_UserManagement\\V1\\Rest\\User\\Controller' => [
                'collection' => [
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => true,
                ],
                'entity' => [
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            'API_UserManagement\\V1\\Rpc\\ValidToken\\Controller' => \API_UserManagement\V1\Rpc\ValidToken\ValidTokenControllerFactory::class,
            'API_UserManagement\\V1\\Rpc\\WelcomeCode\\Controller' => \API_UserManagement\V1\Rpc\WelcomeCode\WelcomeCodeControllerFactory::class,
        ],
    ],
    'zf-rpc' => [
        'API_UserManagement\\V1\\Rpc\\ValidToken\\Controller' => [
            'service_name' => 'ValidToken',
            'http_methods' => [
                0 => 'POST',
            ],
            'route_name' => 'api_user-management.rpc.valid-token',
        ],
        'API_UserManagement\\V1\\Rpc\\WelcomeCode\\Controller' => [
            'service_name' => 'WelcomeCode',
            'http_methods' => [
                0 => 'GET',
            ],
            'route_name' => 'api_user-management.rpc.welcome-code',
        ],
    ],
];
