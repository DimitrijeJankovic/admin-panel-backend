<?php
return [
    'service_manager' => [
        'factories' => [
            \API_Inventory\V1\Rest\Producers\ProducersResource::class => \API_Inventory\V1\Rest\Producers\ProducersResourceFactory::class,
            \API_Inventory\V1\Rest\Countries\CountriesResource::class => \API_Inventory\V1\Rest\Countries\CountriesResourceFactory::class,
            \API_Inventory\V1\Rest\Materials\MaterialsResource::class => \API_Inventory\V1\Rest\Materials\MaterialsResourceFactory::class,
            \API_Inventory\V1\Rest\MaterialTypes\MaterialTypesResource::class => \API_Inventory\V1\Rest\MaterialTypes\MaterialTypesResourceFactory::class,
        ],
    ],
    'router' => [
        'routes' => [
            'api_inventory.rest.producers' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/producers[/:producers_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\Producers\\Controller',
                    ],
                ],
            ],
            'api_inventory.rest.countries' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/countries[/:countries_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\Countries\\Controller',
                    ],
                ],
            ],
            'api_inventory.rest.materials' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/materials[/:materials_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\Materials\\Controller',
                    ],
                ],
            ],
            'api_inventory.rest.material-types' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/material-types[/:material_types_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\MaterialTypes\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'zf-versioning' => [
        'uri' => [
            1 => 'api_inventory.rest.producers',
            0 => 'api_inventory.rest.countries',
            2 => 'api_inventory.rest.materials',
            3 => 'api_inventory.rest.material-types',
        ],
    ],
    'zf-rest' => [
        'API_Inventory\\V1\\Rest\\Producers\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\Producers\ProducersResource::class,
            'route_name' => 'api_inventory.rest.producers',
            'route_identifier_name' => 'producers_id',
            'collection_name' => 'producers',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PUT',
                2 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_Inventory\V1\Rest\Producers\ProducersEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\Producers\ProducersCollection::class,
            'service_name' => 'Producers',
        ],
        'API_Inventory\\V1\\Rest\\Countries\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\Countries\CountriesResource::class,
            'route_name' => 'api_inventory.rest.countries',
            'route_identifier_name' => 'countries_id',
            'collection_name' => 'countries',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_Inventory\V1\Rest\Countries\CountriesEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\Countries\CountriesCollection::class,
            'service_name' => 'Countries',
        ],
        'API_Inventory\\V1\\Rest\\Materials\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\Materials\MaterialsResource::class,
            'route_name' => 'api_inventory.rest.materials',
            'route_identifier_name' => 'materials_id',
            'collection_name' => 'materials',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PUT',
                2 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_Inventory\V1\Rest\Materials\MaterialsEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\Materials\MaterialsCollection::class,
            'service_name' => 'Materials',
        ],
        'API_Inventory\\V1\\Rest\\MaterialTypes\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\MaterialTypes\MaterialTypesResource::class,
            'route_name' => 'api_inventory.rest.material-types',
            'route_identifier_name' => 'material_types_id',
            'collection_name' => 'material_types',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_Inventory\V1\Rest\MaterialTypes\MaterialTypesEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\MaterialTypes\MaterialTypesCollection::class,
            'service_name' => 'MaterialTypes',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'API_Inventory\\V1\\Rest\\Producers\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\Countries\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\Materials\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\MaterialTypes\\Controller' => 'Json',
        ],
        'accept_whitelist' => [
            'API_Inventory\\V1\\Rest\\Producers\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\Countries\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\Materials\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\MaterialTypes\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'API_Inventory\\V1\\Rest\\Producers\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\Countries\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\Materials\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\MaterialTypes\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'zf-hal' => [
        'metadata_map' => [
            \API_Inventory\V1\Rest\Producers\ProducersEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.producers',
                'route_identifier_name' => 'producers_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\Producers\ProducersCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.producers',
                'route_identifier_name' => 'producers_id',
                'is_collection' => true,
            ],
            \API_Inventory\V1\Rest\Countries\CountriesEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.countries',
                'route_identifier_name' => 'countries_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\Countries\CountriesCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.countries',
                'route_identifier_name' => 'countries_id',
                'is_collection' => true,
            ],
            \API_Inventory\V1\Rest\Materials\MaterialsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.materials',
                'route_identifier_name' => 'materials_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\Materials\MaterialsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.materials',
                'route_identifier_name' => 'materials_id',
                'is_collection' => true,
            ],
            \API_Inventory\V1\Rest\MaterialTypes\MaterialTypesEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.material-types',
                'route_identifier_name' => 'material_types_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\MaterialTypes\MaterialTypesCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.material-types',
                'route_identifier_name' => 'material_types_id',
                'is_collection' => true,
            ],
        ],
    ],
    'zf-mvc-auth' => [
        'authorization' => [
            'API_Inventory\\V1\\Rest\\Producers\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => true,
                    'PATCH' => false,
                    'DELETE' => true,
                ],
            ],
            'API_Inventory\\V1\\Rest\\Countries\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => false,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
            ],
            'API_Inventory\\V1\\Rest\\Materials\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => true,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => true,
                    'PATCH' => false,
                    'DELETE' => true,
                ],
            ],
            'API_Inventory\\V1\\Rest\\MaterialTypes\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
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
        'factories' => [],
    ],
    'zf-rpc' => [],
];
