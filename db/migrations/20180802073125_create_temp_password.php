<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateTempPassword extends AbstractMigration
{
    public function up()
    {
        $temp_password = $this->table('temp_password', ['primary_key' => 'id']);
        $temp_password
              ->addColumn('user_id', 'integer')
              ->addForeignKey('user_id', 'oauth_clients', 'id', ['delete'=> 'RESTRICT', 'update'=> 'RESTRICT'])
              ->addColumn('token', 'string', ['limit' => 225])
              ->addColumn('created', 'integer', ['limit' => MysqlAdapter::INT_BIG])               
              ->create();
    }
    
    
    public function down()
    {
        $this->dropTable('temp_password');
    }
}
