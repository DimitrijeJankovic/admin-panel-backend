<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateDeliveryTypes extends AbstractMigration
{
    public function up()
    {
        $deliveryTypes = $this->table('delivery_types', ['primary_key' => 'id']);
        $deliveryTypes 
                ->addColumn('name', 'string', ['limit' => 225])
                ->addIndex(['name'], ['unique' => true])
                ->create();
        
        $deliveryTypes->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('delivery_types');
    }
}
