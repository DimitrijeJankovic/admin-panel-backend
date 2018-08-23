<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateOrderItemCalculation extends AbstractMigration
{
    public function up()
    {
        $orderItemCalculation = $this->table('order_item_calculation', ['primary_key' => 'id']);
        $orderItemCalculation 
                ->addColumn('order_items_id', 'integer', ['limit' => 225])
                ->addColumn('width_cut', 'float')
                ->create();
        
        $orderItemCalculation->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('order_item_calculation');
    }
}
