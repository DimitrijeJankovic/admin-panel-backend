<?php
namespace API_Inventory\V1\Rest\OrderItems;

class OrderItemsResourceFactory
{
    public function __invoke($services)
    {
        return new OrderItemsResource($services->get('admin_panel_db'));
    }
}
