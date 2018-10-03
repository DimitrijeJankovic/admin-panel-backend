<?php
namespace API_Inventory\V1\Rest\OrderItemPhoto;

class OrderItemPhotoResourceFactory
{
    public function __invoke($services)
    {
        return new OrderItemPhotoResource($services->get('admin_panel_db'));
    }
}
