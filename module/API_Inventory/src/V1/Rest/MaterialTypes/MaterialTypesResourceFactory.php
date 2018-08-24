<?php
namespace API_Inventory\V1\Rest\MaterialTypes;

class MaterialTypesResourceFactory
{
    public function __invoke($services)
    {
        return new MaterialTypesResource($services->get('admin_panel_db'));
    }
}
