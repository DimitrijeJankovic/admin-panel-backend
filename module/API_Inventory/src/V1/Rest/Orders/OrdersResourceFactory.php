<?php
namespace API_Inventory\V1\Rest\Orders;

class OrdersResourceFactory
{
    public function __invoke($services)
    {
        return new OrdersResource($services->get('admin_panel_db'));
    }
}
