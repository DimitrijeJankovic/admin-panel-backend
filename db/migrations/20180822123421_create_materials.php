<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateMaterials extends AbstractMigration
{
    public function up()
    {
        $materials = $this->table('materials', ['primary_key' => 'id']);
        $materials 
                ->addColumn('name', 'string', ['limit' => 225])
                ->addColumn('type_id', 'integer')
                ->addColumn('original_width', 'float')
                ->addColumn('original_height', 'float')
                ->addColumn('original_depth', 'float')
                ->addColumn('code', 'string', ['limit' => 255])
                ->addColumn('description_m', 'text')
                ->addColumn('image_url', 'text')
                ->create();
        
        $materials->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('materials');
    }
}
