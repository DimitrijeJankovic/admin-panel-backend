<?php
namespace API_Inventory\V1\Rest\MaterialsPhoto;

class MaterialsPhotoResourceFactory
{
    public function __invoke($services)
    {
        return new MaterialsPhotoResource($services->get('admin_panel_db'));
    }
}
