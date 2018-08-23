<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateUserTypes extends AbstractMigration
{
    public function up()
    {
        $userTypes = $this->table('user_types', ['primary_key' => 'id']);
        $userTypes 
                ->addColumn('type_name', 'string')
                ->create();
        
        $userTypes->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('user_types');
    }
}
