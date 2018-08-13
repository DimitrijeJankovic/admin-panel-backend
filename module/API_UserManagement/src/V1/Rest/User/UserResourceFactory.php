<?php
namespace API_UserManagement\V1\Rest\User;

class UserResourceFactory
{
    public function __invoke($services)
    {
        return new UserResource($services->get('admin_panel_db'));
    }
}
