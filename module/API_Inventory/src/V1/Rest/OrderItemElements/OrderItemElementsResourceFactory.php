<?php
namespace API_Inventory\V1\Rest\OrderItemElements;

class OrderItemElementsResourceFactory
{
    public function __invoke($services)
    {
        return new OrderItemElementsResource($services->get('admin_panel_db'));
    }
}
