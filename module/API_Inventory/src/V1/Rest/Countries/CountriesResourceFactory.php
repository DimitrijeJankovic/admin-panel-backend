<?php
namespace API_Inventory\V1\Rest\Countries;

class CountriesResourceFactory
{
    public function __invoke($services)
    {
        return new CountriesResource($services->get('admin_panel_db'));
    }
}
