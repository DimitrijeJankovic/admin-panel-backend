<?php
namespace API_Inventory\V1\Rest\Producers;

class ProducersResourceFactory
{
    public function __invoke($services)
    {
        return new ProducersResource($services->get('admin_panel_db'));
    }
}
