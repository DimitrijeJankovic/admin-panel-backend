<?php
namespace API_Inventory\V1\Rest\OrdersFolderLeyaout;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;
use Zend\Db\Sql\Select;

class OrdersFolderLeyaoutResource extends AbstractResourceListener
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
        return new ApiProblem(405, 'The POST method has not been defined');
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
        
        # Get material
        $getOrders = "SELECT `order`.id AS order_id ,user_id, date_created, date_in_progress, 
                        date_finished, date_delivery,  payment, price, adress, adress1, state, 
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
                $orders[$i]['order_items'] = $orderItems;
                
            }
            
        }
        
        for($i = 0; $i < count($orders); $i++){
            for($j = 0; $j < count($orders[$i]['order_items']); $j++){

                // get all elements for order_item
                $findElements = $sql->select()->from('order_items_elements')->where(['order_items_id' => $orders[$i]['order_items'][$j]['id']]);
                try { $orderItemsElements = $adapter->query($sql->getSqlStringForSqlObject($findElements), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
                catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
                
                $orders[$i]['order_items'][$j]['item_elements'] = $orderItemsElements;
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
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }
}
