<?php
namespace API_Inventory\V1\Rest\OrderPhoto;

class OrderPhotoResourceFactory
{
    public function __invoke($services)
    {
        return new OrderPhotoResource($services->get('admin_panel_db'));
    }
}
