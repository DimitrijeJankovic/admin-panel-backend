<?php
namespace API_Inventory\V1\Rest\OrderStatus;

class OrderStatusResourceFactory
{
    public function __invoke($services)
    {
        return new OrderStatusResource($services->get('admin_panel_db'));
    }
}
