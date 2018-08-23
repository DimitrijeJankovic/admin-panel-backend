<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateOrderItemsElements extends AbstractMigration
{
    public function up()
    {
        $orderItemsElements = $this->table('order_items_elements', ['primary_key' => 'id']);
        $orderItemsElements 
                ->addColumn('order_items_id', 'integer', ['limit' => 225])
                ->addColumn('width', 'float')
                ->addColumn('height', 'float')
                ->create();
        
        $orderItemsElements->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('order_items_elements');
    }
}
