<?php
namespace API_UserManagement\V1\Rest\ForgotPassword;

class ForgotPasswordResourceFactory
{
    public function __invoke($services)
    {
        return new ForgotPasswordResource($services->get('admin_panel_db'));
    }
}
