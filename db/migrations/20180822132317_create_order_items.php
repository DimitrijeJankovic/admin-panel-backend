<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateOrderItems extends AbstractMigration
{
    public function up()
    {
        $orderItems = $this->table('order_items', ['primary_key' => 'id']);
        $orderItems 
                ->addColumn('order_id', 'integer', ['limit' => 225])
                ->addColumn('material_id', 'integer', ['limit' => 225])
                ->addColumn('quantity', 'integer')
                ->addColumn('materials_custom_id', 'integer')
                ->addColumn('photo_attach_image', 'text', ['null' => true])
                ->create();
        
        $orderItems->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('order_items');
    }
}
