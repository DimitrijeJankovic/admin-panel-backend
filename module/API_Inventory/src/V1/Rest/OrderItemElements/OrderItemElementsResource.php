<?php
namespace API_Inventory\V1\Rest\OrderItemElements;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;
use Zend\Db\Sql\Select;

class OrderItemElementsResource extends AbstractResourceListener
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
        
        if((!isset($data->id) || empty($data->id)) ||
           (!isset($data->order_items_id) || empty($data->order_items_id)) ||
           (!isset($data->height) || empty($data->height)) ||
           (!isset($data->width) || empty($data->width))
        ){            
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);           
        }
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        $find = $sql->select()->from('order_items_elements')->where(['id' => $data->id, 'order_items_id' => $data->order_items_id]);
        try { $itemElement = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        // if item element exists update it, if not insert new
        if($itemElement){
            
            $updateItemElement = $sql->update('order_items_elements')
                    ->set([  
                            'width' => $data->width,
                            'height' => $data->height
                        ])
                    ->where(['id' => $data->id, 'order_items_id' => $data->order_items_id]);
            
            try { $editItemElement = $adapter->query($sql->getSqlStringForSqlObject($updateItemElement), $adapter::QUERY_MODE_EXECUTE); }
            catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
            
            return new ApiProblem(200, $this->messages['Item element change'], null, $this->messages['Success'], []);
            
        }else{
            
            # Insert data in table
            $createItemElement = $sql->insert('order_items_elements')->values([
                'width' => $data->width,
                'height' => $data->height,
                'order_items_id' => $data->order_items_id
            ]);

            try { $newItemElement = $adapter->query($sql->getSqlStringForSqlObject($createItemElement), $adapter::QUERY_MODE_EXECUTE); }
            catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }

            return new ApiProblem(200, $this->messages['Item element created'], null, $this->messages['Success'], []);
            
        }
        
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id)
    {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
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
        if((!isset($id) || empty($id))){
            return new ApiProblem(412, $this->messages['Order item element id must be provided'], null, $this->messages['Warning'], []);       
         }
    
        $adapter = $this->adapter; 
        $sql = new Sql($adapter);
        
        // get order item
        $find = $sql->select()->from('order_items_elements')->where(['id' => $id]);
        try { $orderItemElement = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        return $orderItemElement;
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
        
        $getItemElements = $sql->select()->from('order_items_elements');
        try { $elements = $adapter->query($sql->getSqlStringForSqlObject($getItemElements), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        return $elements;
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
