<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class UpdateOauthClients extends AbstractMigration
{
    public function up()
    {
        $oauth_clients = $this->table('oauth_clients');
        $oauth_clients 
                ->addColumn('address', 'string', ['limit' => 80])
                ->addColumn('address1', 'string', ['limit' => 80, 'null' => true])
                ->addColumn('city', 'string', ['limit' => 60])
                ->addColumn('state', 'string', ['limit' => 60])
                ->addColumn('country', 'integer')
                ->addColumn('phone', 'string', ['limit' => 60])
                ->addColumn('user_type_id', 'integer')
                
                ->removeColumn('redirect_uri')
                ->removeColumn('grant_types')
                ->removeColumn('scope')
                ->removeColumn('token')
                ->removeColumn('fb_user')
                ->update(); 
        
    }
    
    
    public function down()
    {
        $oauth_clients = $this->table('oauth_clients');
        $oauth_clients
                ->removeColumn('address')
                ->removeColumn('address1')
                ->removeColumn('city')
                ->removeColumn('state')
                ->removeColumn('country')
                ->removeColumn('phone')
                ->removeColumn('user_type_id')
                
                ->addColumn('redirect_uri', 'string', ['limit' => 2000])
                ->addColumn('grant_types', 'string', ['limit' => 80, 'null' => true])
                ->addColumn('scope', 'string', ['limit' => 2000, 'null' => true])
                ->addColumn('token', 'string', ['limit' => 2000, 'null' => true])
                ->addColumn('fb_user', 'boolean', ['signed' => false])
                ->update();  
    }
}
