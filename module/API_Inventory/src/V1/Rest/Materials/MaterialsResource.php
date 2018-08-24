<?php
namespace API_Inventory\V1\Rest\Materials;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;
use Zend\Db\Sql\Select;

class MaterialsResource extends AbstractResourceListener
{
    private $adapter;
    private $messages;
    
    public function __construct(Adapter $adapter) {
        
        $this->adapter = $adapter;
        
        # Get language       
        $language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtoupper($_SERVER['HTTP_ACCEPT_LANGUAGE']) : "EN";
        $lang_messages = new LanguageService($language);
        $this->messages = $lang_messages->messages;
    }
    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        if((!isset($data->code) || empty($data->code)) || !isset($data->depth) ||
           (!isset($data->height) || empty($data->height)) ||
           (!isset($data->name) || empty($data->name)) ||
           (!isset($data->type) || empty($data->type)) ||
           (!isset($data->width) || empty($data->width))
        ){            
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);           
        }
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        # Insert user data in table
        $createMaterial = $sql->insert('materials')->values([
            'name' => $data->name,
            'type_id' => $data->type,
            'original_width' => $data->width,
            'original_height' => $data->height,
            'original_depth' => $data->depth,
            'code' => $data->code,
            'description_m' => isset($data->desc)? $data->desc : ""
        ]);
       
        try { $materialData = $adapter->query($sql->getSqlStringForSqlObject($createMaterial), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }

        return new ApiProblem(200, $this->messages['Material created'], null, $this->messages['Success'], []);
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        $adapter = $this->adapter; 
        $sql = new Sql($adapter);
        
        # Check if exists
        $find = $sql->select()->from('materials')->where(['id' => $id]);
        try { $material = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        if (empty($material)) { return new ApiProblem(409, $this->messages['Material not found'], null, $this->messages['Error'], []); }
        
        # del produces
        $delete = $sql->delete('materials')->where(['id' => $id]);
        
        try{ $delMaterial = $adapter->query($sql->getSqlStringForSqlObject($delete), $adapter::QUERY_MODE_EXECUTE); }
        catch(\Zend\Db\Adapter\Adapter $e){ return new ApiProblem(409, $e->getPrevious()->getMessage()); }
        
        return true;
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id)
    {
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = [])
    {
        $adapter = $this->adapter; 
        $sql = new Sql($adapter);
        
        # Get produces
        $getMaterials = $sql->select()
                ->from('materials')
                ->columns(['id AS material_id', 'name AS material_name', 'type_id', 'original_width', 'original_height', 'original_depth', 'code', 'description_m', 'image_url'])
                ->join('material_type', 'material_type.id = materials.type_id', ['name'], Select::JOIN_INNER);
        
        try { $materials = $adapter->query($sql->getSqlStringForSqlObject($getMaterials), $adapter::QUERY_MODE_EXECUTE)->toArray();}
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

        return $materials;
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Patch (partial in-place update) a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patchList($data)
    {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data)
    {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
