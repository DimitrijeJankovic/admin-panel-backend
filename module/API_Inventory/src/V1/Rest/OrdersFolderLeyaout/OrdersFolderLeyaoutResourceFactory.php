<?php
namespace API_Inventory\V1\Rest\OrdersFolderLeyaout;

class OrdersFolderLeyaoutResourceFactory
{
    public function __invoke($services)
    {
        return new OrdersFolderLeyaoutResource($services->get('admin_panel_db'));
    }
}
