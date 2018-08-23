<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateMaterialType extends AbstractMigration
{
    public function up()
    {
        $materialType = $this->table('material_type', ['primary_key' => 'id']);
        $materialType 
                ->addColumn('name', 'string', ['limit' => 225])
                ->addIndex(['name'], ['unique' => true])
                ->create();
        
        $materialType->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('material_type');
    }
}
