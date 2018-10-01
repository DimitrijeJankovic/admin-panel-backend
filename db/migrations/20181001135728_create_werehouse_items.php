<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateWerehouseItems extends AbstractMigration
{
    public function up()
    {
        $wereHouseItem = $this->table('werehouse_item', ['primary_key' => 'id']);
        $wereHouseItem
                ->addColumn('werehouse_id', 'integer')
                ->addColumn('quantity', 'integer')
                ->addColumn('LOT_number', 'string', ['limit' => 200])
                ->addColumn('date_finished', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                ->create();
        
        $wereHouseItem->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('werehouse_item');
    }
}
