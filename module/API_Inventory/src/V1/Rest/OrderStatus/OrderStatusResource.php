<?php
namespace API_Inventory\V1\Rest\OrderStatus;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;

class OrderStatusResource extends AbstractResourceListener
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
        
        $getAllStatuses = $sql->select()->from('status_types');
        try { $statuses = $adapter->query($sql->getSqlStringForSqlObject($getAllStatuses), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        return $statuses;
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
