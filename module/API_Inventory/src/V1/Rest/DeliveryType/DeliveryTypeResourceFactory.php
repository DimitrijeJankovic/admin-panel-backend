<?php
namespace API_Inventory\V1\Rest\DeliveryType;

class DeliveryTypeResourceFactory
{
    public function __invoke($services)
    {
        return new DeliveryTypeResource($services->get('admin_panel_db'));
    }
}
