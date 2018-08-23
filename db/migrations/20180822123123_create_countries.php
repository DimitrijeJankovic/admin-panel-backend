<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateCountries extends AbstractMigration
{
    public function up()
    {
        $countries = $this->table('countries', ['primary_key' => 'id']);
        $countries 
                ->addColumn('name', 'string', ['limit' => 225])
                ->addIndex(['name'], ['unique' => true])
                ->create();
        
        $countries->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('countries');
    }
}
