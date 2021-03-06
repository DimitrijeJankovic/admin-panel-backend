<?php
namespace API_Inventory\V1\Rest\Orders;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;
use Zend\Db\Sql\Select;

class OrdersResource extends AbstractResourceListener
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
        
        if((!isset($data->adress) || empty($data->adress)) ||
           (!isset($data->country) || empty($data->country)) ||
           (!isset($data->date_created) || empty($data->date_created)) ||
           (!isset($data->requested_date) || empty($data->requested_date)) ||
           (!isset($data->delivery_type_id) || empty($data->delivery_type_id)) ||
           (!isset($data->payment)) ||
           (!isset($data->state) || empty($data->state)) ||
           (!isset($data->status_id) || empty($data->status_id))
        ){            
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);           
        }
        
        #get user id from token
        $user_id = (int) $this->getIdentity()->getAuthenticationIdentity()["user_id"];
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        
        # Insert user data in table
        $create_order = $sql->insert('order')->values([
            'user_id' => $user_id,
            'date_created' => $data->date_created,
            'date_in_progress' => isset($data->date_in_progress)? $data->date_in_progress : null,
            'date_finished' => isset($data->date_finished)? $data->date_finished : null,
            'date_delivery' => isset($data->date_delivery)? $data->date_delivery : null,
            'requested_date' => $data->requested_date,
            'status_id' => $data->status_id,
            'payment' => $data->payment,
            'price' => isset($data->price)? $data->price : null,
            'adress' => $data->adress,
            'adress1' => isset($data->adress1)? $data->adress1 : null,
            'state' => $data->state,
            'country' => $data->country,
            'delivery_type_id' => $data->delivery_type_id,
            'supplied_by_users_id' => null
        ]);
       
        try { $new_order = $adapter->query($sql->getSqlStringForSqlObject($create_order), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
            
        return new ApiProblem(200, $this->messages['Order created'], null, $this->messages['Success'], []);
        
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
            return new ApiProblem(412, $this->messages['Order id must be provided'], null, $this->messages['Warning'], []);       
         }
    
        $adapter = $this->adapter; 
        $sql = new Sql($adapter);
        
        // get order
        $find = $sql->select()->from('order')->where(['id' => $id]);
        try { $order = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        if(empty($order)){
            return new ApiProblem(404, $this->messages['Order not found'], null, $this->messages['Warning'], []);  
        }
        
        return $order;
        
        
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
        
        # Get material
        $getOrders = "SELECT `order`.id AS order_id ,user_id, date_created, date_in_progress, 
                        date_finished, date_delivery, requested_date, payment, price, adress, adress1, state, 
                        supplied_by_users_id, status_types.name AS status_type, countries.name 
                        AS countrie, delivery_types.name AS delivery_type
                        FROM `order`
                        INNER JOIN status_types ON `order`.status_id = status_types.id
                        INNER JOIN countries ON `order`.`country` = countries.id
                        INNER JOIN delivery_types on `order`.`delivery_type_id` = delivery_types.id";
        
        try { $orders = $adapter->query($getOrders, $adapter::QUERY_MODE_EXECUTE)->toArray();}
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

        if(!empty($orders)){
            
            for($i = 0; $i < count($orders); $i++){
                // get all order items
                $find = $sql->select()->from('order_items')->where(['order_id' => $orders[$i]['order_id']]);
                try { $orderItems = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
                catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
                
                // add items to orders array
                $orders[$i]['more']['order_items'] = $orderItems;
                
                $elements = [];
                
                foreach ($orderItems as $orderItem){
                    
                    $getItemElements = $sql->select()->from('order_items_elements')->where(['order_items_id' => $orderItem['id']]);
                    try { $itemElements = $adapter->query($sql->getSqlStringForSqlObject($getItemElements), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
                    catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
                    
                    foreach ($itemElements as $element){
                        $elements[] = $element;
                    }
                }
                
                $orders[$i]['more']['order_items_elements'] = $elements;
                
                $elements = [];
            }
            
        }
        
        return $orders;
     
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
        if((!isset($data->adress) || empty($data->adress)) ||
           (!isset($data->country) || empty($data->country)) ||
           (!isset($data->date_created) || empty($data->date_created)) ||
           (!isset($data->requested_date) || empty($data->requested_date)) ||
           (!isset($data->delivery_type_id) || empty($data->delivery_type_id)) ||
           (!isset($data->payment)) ||
           (!isset($data->state) || empty($data->state)) ||
           (!isset($data->status_id) || empty($data->status_id))
        ){            
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);           
        }
        
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        $find = $sql->select()->from('order')->where(['id' => id]);
        try { $order = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        if($order){
            return new ApiProblem(404, $this->messages['Order not found'], null, $this->messages['Warning'], []);
        }
        
        $update_order = $sql->update('order')
                ->set([  
                    'date_in_progress' => isset($data->date_in_progress)? $data->date_in_progress : null,
                    'date_finished' => isset($data->date_finished)? $data->date_finished : null,
                    'date_delivery' => isset($data->date_delivery)? $data->date_delivery : null,
                    'requested_date' => $data->requested_date,
                    'status_id' => $data->status_id,
                    'payment' => $data->payment,
                    'price' => isset($data->price)? $data->price : null,
                    'adress' => $data->adress,
                    'adress1' => isset($data->adress1)? $data->adress1 : null,
                    'state' => $data->state,
                    'country' => $data->country,
                    'delivery_type_id' => $data->delivery_type_id,
                    'supplied_by_users_id' => null,
                ])
                ->where(['id' => $id]);
            
        try { $edit_order = $adapter->query($sql->getSqlStringForSqlObject($update_order), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
            
        return new ApiProblem(200, $this->messages['Order change'], null, $this->messages['Success'], []);
    }
}
