<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateDelivery extends AbstractMigration
{
    public function up()
    {
        $delivery = $this->table('delivery', ['primary_key' => 'id']);
        $delivery
                ->addColumn('first_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('last_name', 'string', ['limit' => 255, 'null' => true])
                ->addColumn('address', 'string', ['limit' => 80])
                ->addColumn('address1', 'string', ['limit' => 80, 'null' => true])
                ->addColumn('city', 'string', ['limit' => 60])
                ->addColumn('state', 'string', ['limit' => 60])
                ->addColumn('country', 'integer')
                ->addColumn('phone', 'string', ['limit' => 60])
                ->addColumn('email', 'string', ['limit' => 160])
                ->addColumn('date_created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
                ->addColumn('date_delivery_start', 'timestamp', ['null' => true])
                ->addColumn('date_delivery', 'integer')
                ->create();
        
        $delivery->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('delivery');
    }
}
