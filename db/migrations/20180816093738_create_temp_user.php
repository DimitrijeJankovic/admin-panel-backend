<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateTempUser extends AbstractMigration
{
    public function up()
    {
        $temp_user = $this->table('temp_user', ['primary_key' => 'id']);
        $temp_user 
                ->addColumn('client_id', 'string', ['limit' => 225])
                ->addIndex(['client_id'], ['unique' => true])
                ->addColumn('first_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('last_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('client_secret', 'string', ['limit' => 80])
                ->addColumn('registration_code', 'integer')
                ->create();
        
        $temp_user->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('temp_user');
    }
}
