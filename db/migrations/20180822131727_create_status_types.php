<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateStatusTypes extends AbstractMigration
{
    public function up()
    {
        $statusTypes = $this->table('status_types', ['primary_key' => 'id']);
        $statusTypes 
                ->addColumn('name', 'string', ['limit' => 225])
                ->addIndex(['name'], ['unique' => true])
                ->create();
        
        $statusTypes->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('status_types');
    }
}
