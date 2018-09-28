<?php
return [
    'service_manager' => [
        'factories' => [
            \API_Inventory\V1\Rest\Producers\ProducersResource::class => \API_Inventory\V1\Rest\Producers\ProducersResourceFactory::class,
            \API_Inventory\V1\Rest\Countries\CountriesResource::class => \API_Inventory\V1\Rest\Countries\CountriesResourceFactory::class,
            \API_Inventory\V1\Rest\Materials\MaterialsResource::class => \API_Inventory\V1\Rest\Materials\MaterialsResourceFactory::class,
            \API_Inventory\V1\Rest\MaterialTypes\MaterialTypesResource::class => \API_Inventory\V1\Rest\MaterialTypes\MaterialTypesResourceFactory::class,
            \API_Inventory\V1\Rest\MaterialsPhoto\MaterialsPhotoResource::class => \API_Inventory\V1\Rest\MaterialsPhoto\MaterialsPhotoResourceFactory::class,
            \API_Inventory\V1\Rest\Orders\OrdersResource::class => \API_Inventory\V1\Rest\Orders\OrdersResourceFactory::class,
            \API_Inventory\V1\Rest\OrderItems\OrderItemsResource::class => \API_Inventory\V1\Rest\OrderItems\OrderItemsResourceFactory::class,
            \API_Inventory\V1\Rest\OrderItemElements\OrderItemElementsResource::class => \API_Inventory\V1\Rest\OrderItemElements\OrderItemElementsResourceFactory::class,
            'API_Inventory\\V1\\Rest\\OrdersLeyaout\\OrdersLeyaoutResource' => 'API_Inventory\\V1\\Rest\\OrdersLeyaout\\OrdersLeyaoutResourceFactory',
            \API_Inventory\V1\Rest\OrdersFolderLeyaout\OrdersFolderLeyaoutResource::class => \API_Inventory\V1\Rest\OrdersFolderLeyaout\OrdersFolderLeyaoutResourceFactory::class,
            \API_Inventory\V1\Rest\OrderStatus\OrderStatusResource::class => \API_Inventory\V1\Rest\OrderStatus\OrderStatusResourceFactory::class,
            \API_Inventory\V1\Rest\DeliveryType\DeliveryTypeResource::class => \API_Inventory\V1\Rest\DeliveryType\DeliveryTypeResourceFactory::class,
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
            'api_inventory.rest.materials-photo' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/materials-photo[/:materials_photo_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\MaterialsPhoto\\Controller',
                    ],
                ],
            ],
            'api_inventory.rest.orders' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/orders[/:orders_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\Orders\\Controller',
                    ],
                ],
            ],
            'api_inventory.rest.order-items' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/order-items[/:order_items_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\OrderItems\\Controller',
                    ],
                ],
            ],
            'api_inventory.rest.order-item-elements' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/order-item-elements[/:order_item_elements_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\OrderItemElements\\Controller',
                    ],
                ],
            ],
            'api_inventory.rest.orders-folder-leyaout' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/orders-folder-leyaout[/:orders_folder_leyaout_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\OrdersFolderLeyaout\\Controller',
                    ],
                ],
            ],
            'api_inventory.rest.order-status' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/order-status[/:order_status_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\OrderStatus\\Controller',
                    ],
                ],
            ],
            'api_inventory.rest.delivery-type' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/delivery-type[/:delivery_type_id]',
                    'defaults' => [
                        'controller' => 'API_Inventory\\V1\\Rest\\DeliveryType\\Controller',
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
            4 => 'api_inventory.rest.materials-photo',
            5 => 'api_inventory.rest.orders',
            6 => 'api_inventory.rest.order-items',
            7 => 'api_inventory.rest.order-item-elements',
            9 => 'api_inventory.rest.orders-folder-leyaout',
            10 => 'api_inventory.rest.order-status',
            11 => 'api_inventory.rest.delivery-type',
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
        'API_Inventory\\V1\\Rest\\MaterialsPhoto\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\MaterialsPhoto\MaterialsPhotoResource::class,
            'route_name' => 'api_inventory.rest.materials-photo',
            'route_identifier_name' => 'materials_photo_id',
            'collection_name' => 'materials_photo',
            'entity_http_methods' => [
                0 => 'POST',
                1 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_Inventory\V1\Rest\MaterialsPhoto\MaterialsPhotoEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\MaterialsPhoto\MaterialsPhotoCollection::class,
            'service_name' => 'MaterialsPhoto',
        ],
        'API_Inventory\\V1\\Rest\\Orders\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\Orders\OrdersResource::class,
            'route_name' => 'api_inventory.rest.orders',
            'route_identifier_name' => 'orders_id',
            'collection_name' => 'orders',
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
            'entity_class' => \API_Inventory\V1\Rest\Orders\OrdersEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\Orders\OrdersCollection::class,
            'service_name' => 'Orders',
        ],
        'API_Inventory\\V1\\Rest\\OrderItems\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\OrderItems\OrderItemsResource::class,
            'route_name' => 'api_inventory.rest.order-items',
            'route_identifier_name' => 'order_items_id',
            'collection_name' => 'order_items',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PUT',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
                2 => 'PUT',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_Inventory\V1\Rest\OrderItems\OrderItemsEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\OrderItems\OrderItemsCollection::class,
            'service_name' => 'OrderItems',
        ],
        'API_Inventory\\V1\\Rest\\OrderItemElements\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\OrderItemElements\OrderItemElementsResource::class,
            'route_name' => 'api_inventory.rest.order-item-elements',
            'route_identifier_name' => 'order_item_elements_id',
            'collection_name' => 'order_item_elements',
            'entity_http_methods' => [
                0 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_Inventory\V1\Rest\OrderItemElements\OrderItemElementsEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\OrderItemElements\OrderItemElementsCollection::class,
            'service_name' => 'OrderItemElements',
        ],
        'API_Inventory\\V1\\Rest\\OrdersFolderLeyaout\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\OrdersFolderLeyaout\OrdersFolderLeyaoutResource::class,
            'route_name' => 'api_inventory.rest.orders-folder-leyaout',
            'route_identifier_name' => 'orders_folder_leyaout_id',
            'collection_name' => 'orders_folder_leyaout',
            'entity_http_methods' => [
                0 => 'PATCH',
                1 => 'GET',
            ],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_Inventory\V1\Rest\OrdersFolderLeyaout\OrdersFolderLeyaoutEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\OrdersFolderLeyaout\OrdersFolderLeyaoutCollection::class,
            'service_name' => 'OrdersFolderLeyaout',
        ],
        'API_Inventory\\V1\\Rest\\OrderStatus\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\OrderStatus\OrderStatusResource::class,
            'route_name' => 'api_inventory.rest.order-status',
            'route_identifier_name' => 'order_status_id',
            'collection_name' => 'order_status',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_Inventory\V1\Rest\OrderStatus\OrderStatusEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\OrderStatus\OrderStatusCollection::class,
            'service_name' => 'OrderStatus',
        ],
        'API_Inventory\\V1\\Rest\\DeliveryType\\Controller' => [
            'listener' => \API_Inventory\V1\Rest\DeliveryType\DeliveryTypeResource::class,
            'route_name' => 'api_inventory.rest.delivery-type',
            'route_identifier_name' => 'delivery_type_id',
            'collection_name' => 'delivery_type',
            'entity_http_methods' => [],
            'collection_http_methods' => [
                0 => 'GET',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \API_Inventory\V1\Rest\DeliveryType\DeliveryTypeEntity::class,
            'collection_class' => \API_Inventory\V1\Rest\DeliveryType\DeliveryTypeCollection::class,
            'service_name' => 'DeliveryType',
        ],
    ],
    'zf-content-negotiation' => [
        'controllers' => [
            'API_Inventory\\V1\\Rest\\Producers\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\Countries\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\Materials\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\MaterialTypes\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\MaterialsPhoto\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\Orders\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\OrderItems\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\OrderItemElements\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\OrdersFolderLeyaout\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\OrderStatus\\Controller' => 'Json',
            'API_Inventory\\V1\\Rest\\DeliveryType\\Controller' => 'Json',
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
            'API_Inventory\\V1\\Rest\\MaterialsPhoto\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\Orders\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\OrderItems\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\OrderItemElements\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\OrdersFolderLeyaout\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\OrderStatus\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\DeliveryType\\Controller' => [
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
            'API_Inventory\\V1\\Rest\\MaterialsPhoto\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\Orders\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\OrderItems\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\OrderItemElements\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\OrdersFolderLeyaout\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\OrderStatus\\Controller' => [
                0 => 'application/vnd.api_inventory.v1+json',
                1 => 'application/json',
            ],
            'API_Inventory\\V1\\Rest\\DeliveryType\\Controller' => [
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
            \API_Inventory\V1\Rest\MaterialsPhoto\MaterialsPhotoEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.materials-photo',
                'route_identifier_name' => 'materials_photo_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\MaterialsPhoto\MaterialsPhotoCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.materials-photo',
                'route_identifier_name' => 'materials_photo_id',
                'is_collection' => true,
            ],
            \API_Inventory\V1\Rest\Orders\OrdersEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.orders',
                'route_identifier_name' => 'orders_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\Orders\OrdersCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.orders',
                'route_identifier_name' => 'orders_id',
                'is_collection' => true,
            ],
            \API_Inventory\V1\Rest\OrderItems\OrderItemsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.order-items',
                'route_identifier_name' => 'order_items_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\OrderItems\OrderItemsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.order-items',
                'route_identifier_name' => 'order_items_id',
                'is_collection' => true,
            ],
            \API_Inventory\V1\Rest\OrderItemElements\OrderItemElementsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.order-item-elements',
                'route_identifier_name' => 'order_item_elements_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\OrderItemElements\OrderItemElementsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.order-item-elements',
                'route_identifier_name' => 'order_item_elements_id',
                'is_collection' => true,
            ],
            \API_Inventory\V1\Rest\OrdersFolderLeyaout\OrdersFolderLeyaoutEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.orders-folder-leyaout',
                'route_identifier_name' => 'orders_folder_leyaout_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\OrdersFolderLeyaout\OrdersFolderLeyaoutCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.orders-folder-leyaout',
                'route_identifier_name' => 'orders_folder_leyaout_id',
                'is_collection' => true,
            ],
            \API_Inventory\V1\Rest\OrderStatus\OrderStatusEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.order-status',
                'route_identifier_name' => 'order_status_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\OrderStatus\OrderStatusCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.order-status',
                'route_identifier_name' => 'order_status_id',
                'is_collection' => true,
            ],
            \API_Inventory\V1\Rest\DeliveryType\DeliveryTypeEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.delivery-type',
                'route_identifier_name' => 'delivery_type_id',
                'hydrator' => \Zend\Hydrator\ArraySerializable::class,
            ],
            \API_Inventory\V1\Rest\DeliveryType\DeliveryTypeCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'api_inventory.rest.delivery-type',
                'route_identifier_name' => 'delivery_type_id',
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
            'API_Inventory\\V1\\Rest\\MaterialsPhoto\\Controller' => [
                'collection' => [
                    'GET' => false,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => false,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => true,
                ],
            ],
            'API_Inventory\\V1\\Rest\\Orders\\Controller' => [
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
            'API_Inventory\\V1\\Rest\\OrderItems\\Controller' => [
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
                    'DELETE' => false,
                ],
            ],
            'API_Inventory\\V1\\Rest\\OrderItemElements\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => true,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
            ],
            'API_Inventory\\V1\\Rest\\OrdersFolderLeyaout\\Controller' => [
                'collection' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
                'entity' => [
                    'GET' => true,
                    'POST' => false,
                    'PUT' => false,
                    'PATCH' => false,
                    'DELETE' => false,
                ],
            ],
            'API_Inventory\\V1\\Rest\\OrderStatus\\Controller' => [
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
            'API_Inventory\\V1\\Rest\\DeliveryType\\Controller' => [
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
];
