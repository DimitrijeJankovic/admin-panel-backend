<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateWerehouse extends AbstractMigration
{
    public function up()
    {
        $werehouse = $this->table('werehouse', ['primary_key' => 'id']);
        $werehouse
                ->addColumn('user_id', 'integer')
                ->addColumn('material_id', 'integer')
                ->addColumn('price', 'float')
                ->addColumn('vat', 'float')
                ->addColumn('status', 'integer')
                ->create();
        
        $werehouse->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('werehouse');
    }
}
