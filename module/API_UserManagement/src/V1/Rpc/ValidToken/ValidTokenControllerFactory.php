<?php
namespace API_UserManagement\V1\Rpc\ValidToken;

class ValidTokenControllerFactory
{
    public function __invoke($controllers)
    {
        return new ValidTokenController($controllers->get('admin_panel_db'));
    }
}
