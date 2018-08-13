<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateOauthMigration extends AbstractMigration
{
    
    public function change()
    {
        $oauth_clients = $this->table('oauth_clients', ['primary_key' => 'client_id']);
        $oauth_clients
              ->addColumn('client_id', 'string', ['limit' => 225])
              ->addIndex(array('client_id'), array('unique' => true))
              ->addColumn('client_secret', 'string', ['limit' => 80])
              ->addColumn('username', 'string', ['limit' => 80])
              ->addColumn('redirect_uri', 'string', ['limit' => 2000])
              ->addColumn('grant_types', 'string', ['limit' => 80, 'null' => true])
              ->addColumn('scope', 'string', ['limit' => 2000, 'null' => true])
              ->addColumn('user_id', 'string', ['limit' => 255, 'null' => true])  
              ->addColumn('role', 'integer', ['limit' => MysqlAdapter::INT_SMALL])
              ->addColumn('status', 'integer', ['limit' => MysqlAdapter::INT_SMALL])
              ->addColumn('token', 'string', ['limit' => 2000, 'null' => true])
              ->addColumn('first_name', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('last_name', 'string', ['limit' => 255, 'null' => true])
              ->addColumn('fb_user', 'boolean', ['signed' => false])
              ->create();
        
        $oauth_access_tokens = $this->table('oauth_access_tokens', ['primary_key' => 'access_token']);
        $oauth_access_tokens
              ->addColumn('access_token', 'string', ['limit' => 40])
              ->addColumn('client_id', 'string', ['limit' => 80])
              ->addColumn('user_id', 'string', ['limit' => 255])
              ->addColumn('expires', 'timestamp')
              ->addColumn('scope', 'string', ['limit' => 2000, 'null' => true])
              ->create();
        
        $oauth_authorization_codes = $this->table('oauth_authorization_codes', ['primary_key' => 'authorization_code']);
        $oauth_authorization_codes
              ->addColumn('authorization_code', 'string', ['limit' => 40])
              ->addColumn('client_id', 'string', ['limit' => 80])
              ->addColumn('user_id', 'string', ['limit' => 255])
              ->addColumn('expires', 'timestamp')
              ->addColumn('scope', 'string', ['limit' => 2000, 'null' => true])
              ->addColumn('id_token', 'string', ['limit' => 2000, 'null' => true])
              ->create();
        
        $oauth_refresh_tokens = $this->table('oauth_refresh_tokens', ['primary_key' => 'refresh_token']);
        $oauth_refresh_tokens
              ->addColumn('refresh_token', 'string', ['limit' => 40])
              ->addColumn('client_id', 'string', ['limit' => 80])
              ->addColumn('user_id', 'string', ['limit' => 255])
              ->addColumn('expires', 'timestamp')
              ->addColumn('scope', 'string', ['limit' => 2000, 'null' => true])
              ->create();
        
        $oauth_users = $this->table('oauth_users', ['primary_key' => 'username']);
        $oauth_users
              ->addColumn('username', 'string', ['limit' => 255])
              ->addColumn('password', 'string', ['limit' => 2000])
              ->addColumn('first_name', 'string', ['limit' => 255])
              ->addColumn('last_name', 'string', ['limit' => 255])
              ->create();
        
        $oauth_scopes = $this->table('oauth_scopes');
        $oauth_scopes
              ->addColumn('type', 'string', ['limit' => 255, 'default' => "supported"])
              ->addColumn('scope', 'string', ['limit' => 2000])
              ->addColumn('client_id', 'string', ['limit' => 80])
              ->addColumn('is_default', 'integer', ['limit' => MysqlAdapter::INT_SMALL])
              ->create();
        
        $oauth_jwt = $this->table('oauth_jwt', ['primary_key' => 'client_id']);
        $oauth_jwt
              ->addColumn('client_id', 'string', ['limit' => 80])
              ->addColumn('subject', 'string', ['limit' => 80])
              ->addColumn('public_key', 'string', ['limit' => 2000])
              ->create();
    }
}