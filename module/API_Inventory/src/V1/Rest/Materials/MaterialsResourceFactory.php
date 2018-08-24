<?php
namespace API_Inventory\V1\Rest\Materials;

class MaterialsResourceFactory
{
    public function __invoke($services)
    {
        return new MaterialsResource($services->get('admin_panel_db'));
    }
}
