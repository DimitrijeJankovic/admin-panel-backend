<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateProducers extends AbstractMigration
{
    public function up()
    {
        $producers = $this->table('producers', ['primary_key' => 'id']);
        $producers 
                ->addColumn('name', 'string', ['limit' => 225])
                ->addColumn('address', 'string', ['limit' => 80])
                ->addColumn('address1', 'string', ['limit' => 80, 'null' => true])
                ->addColumn('city', 'string', ['limit' => 60])
                ->addColumn('state', 'string', ['limit' => 60])
                ->addColumn('country', 'integer')
                ->addColumn('email', 'string', ['limit' => 225])
                ->addColumn('phone', 'string', ['limit' => 60])
                ->addColumn('web', 'string', ['limit' => 80, 'null' => true])
                ->create();
        
        $producers->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('producers');
    }
}
