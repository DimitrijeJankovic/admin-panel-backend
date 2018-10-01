<?php

use Phinx\Migration\AbstractMigration;
use Phinx\Db\Adapter\MysqlAdapter;

class CreateMaterialsCustom extends AbstractMigration
{
    public function up()
    {
        $materialsCustom = $this->table('materials_custom', ['primary_key' => 'id']);
        $materialsCustom
                ->addColumn('name', 'string', ['limit' => 225])
                ->addColumn('type_id', 'integer')
                ->addColumn('original_width', 'float')
                ->addColumn('original_height', 'float')
                ->addColumn('original_depth', 'float')
                ->addColumn('code', 'string', ['limit' => 225])
                ->addColumn('description_m', 'text', ['null' => true])
                ->addColumn('image_url', 'text', ['null' => true])
                ->create();
        
        $materialsCustom->changeColumn('id', 'integer', ['limit' => MysqlAdapter::INT_BIG, 'signed' => false, 'identity' => true])->save();
    }
    
    
    public function down()
    {
        $this->dropTable('materials_custom');
    }
}
