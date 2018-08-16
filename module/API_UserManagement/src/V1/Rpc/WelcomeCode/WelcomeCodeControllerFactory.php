<?php
namespace API_UserManagement\V1\Rpc\WelcomeCode;

class WelcomeCodeControllerFactory
{
    public function __invoke($controllers)
    {
        return new WelcomeCodeController($controllers->get('admin_panel_db'));
    }
}
