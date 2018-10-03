<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateMaterialGeneralType extends AbstractMigration
{
    public function up()
    {
        $materialGeneralType = $this->table('material_general_type', ['primary_key' => 'id']);
        $materialGeneralType 
                ->addColumn('name', 'string', ['limit' => 225])
                ->addIndex(['name'], ['unique' => true])
                ->create();
        
        $materialGeneralType->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('material_general_type');
    }
}
