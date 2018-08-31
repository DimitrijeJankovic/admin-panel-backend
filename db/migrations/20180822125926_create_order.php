<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateOrder extends AbstractMigration
{
    public function up()
    {
        $order = $this->table('order', ['primary_key' => 'id']);
        $order
                ->addColumn('user_id', 'integer')
                ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('date_in_progress', 'timestamp', ['null' => true])
                ->addColumn('date_finished', 'timestamp', ['null' => true])
                ->addColumn('date_delivery', 'timestamp', ['null' => true])
                ->addColumn('status_id', 'integer', ['default' => "1"])
                ->addColumn('payment', 'boolean', ['default' => "0"])
                ->addColumn('price', 'float', ['null' => true, 'default' => "0"])
                ->addColumn('adress', 'string', ['limit' => 80])
                ->addColumn('adress1', 'string', ['limit' => 80, 'null' => true])
                ->addColumn('state', 'string', ['limit' => 80])
                ->addColumn('country', 'integer')
                ->addColumn('delivery_type_id', 'integer')
                ->addColumn('supplied_by_users_id', 'integer', [ 'null' => true])
                ->create();
        
        $order->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('order');
    }
}
