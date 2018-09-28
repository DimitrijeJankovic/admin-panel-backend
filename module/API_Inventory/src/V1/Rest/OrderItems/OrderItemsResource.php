<?php
namespace API_Inventory\V1\Rest\OrderItems;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;
use Zend\Db\Sql\Select;

class OrderItemsResource extends AbstractResourceListener
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
        
        if((!isset($data->material_id) || empty($data->material_id)) ||
           (!isset($data->order_id) || empty($data->order_id)) ||
           (!isset($data->quantity) || empty($data->quantity))
        ){            
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);           
        }
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
            
        # Insert data in table
        $createOrderItem = $sql->insert('order_items')->values([
            'order_id' => $data->order_id,
            'material_id' => $data->material_id,
            'quantity' => $data->quantity
        ]);

        try { $newOrderItem = $adapter->query($sql->getSqlStringForSqlObject($createOrderItem), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }

        return new ApiProblem(200, $this->messages['Order Item created'], null, $this->messages['Success'], []);
            
        
        
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
            return new ApiProblem(412, $this->messages['Order item id must be provided'], null, $this->messages['Warning'], []);       
         }
    
        $adapter = $this->adapter; 
        $sql = new Sql($adapter);
        
        // get order item
        $find = $sql->select()->from('order_items')->where(['id' => $id]);
        try { $orderItems = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        return $orderItems;
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
        
        $getItems = $sql->select()->from('order_items');
        try { $items = $adapter->query($sql->getSqlStringForSqlObject($getItems), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        return $items;
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
       if((!isset($data->material_id) || empty($data->material_id)) ||
           (!isset($data->order_id) || empty($data->order_id)) ||
           (!isset($data->quantity) || empty($data->quantity))
        ){            
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);           
        }
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        $find = $sql->select()->from('order_items')->where(['id' => $id, 'order_id' => $data->order_id]);
        try { $orderItem = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
            
        $updateOrderItem = $sql->update('order_items')
            ->set([  
                'material_id' => $data->material_id,
                'quantity' => $data->quantity])
            ->where(['id' => $id, 'order_id' => $data->order_id]);
            
        try { $editOrderItem = $adapter->query($sql->getSqlStringForSqlObject($updateOrderItem), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
            
        return new ApiProblem(200, $this->messages['Order Item change'], null, $this->messages['Success'], []);
    }
}
