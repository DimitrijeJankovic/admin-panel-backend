<?php
namespace API_Inventory\V1\Rest\Producers;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;
use Zend\Db\Sql\Select;

class ProducersResource extends AbstractResourceListener
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
        
        if((!isset($data->name) || empty($data->name)) ||
           (!isset($data->address) || empty($data->address)) ||
           (!isset($data->city) || empty($data->city)) ||
           (!isset($data->country) || empty($data->country)) ||
           (!isset($data->state) || empty($data->state)) ||
           (!isset($data->email) || empty($data->email)) ||
           (!isset($data->phone) || empty($data->phone))) {            
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);           
        }
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
   
        # Check if exists
        $find = $sql->select()->from('producers')->where(['name' => $data->name]);
        try { $producer = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        if (!empty($producer)) { return new ApiProblem(409, $this->messages['Produce already exists'], null, $this->messages['Error'], []); }
        
        # Insert user data in table
        $createProducer = $sql->insert('producers')->values([
            'name' => $data->name,
            'address' => $data->address,
            'address1' => isset($data->address1)? $data->address1 : "",
            'city' => $data->city,
            'state' => $data->state,
            'country' => $data->country,
            'email' => $data->email,
            'phone' => $data->phone,
            'web' => isset($data->web)? $data->web : "",
            
        ]);
        
        try { $producerData = $adapter->query($sql->getSqlStringForSqlObject($createProducer), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        return new ApiProblem(200, $this->messages['Producer created'], null, $this->messages['Success'], []);
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
        $find = $sql->select()->from('producers')->where(['id' => $id]);
        try { $producer = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        if (empty($producer)) { return new ApiProblem(409, $this->messages['Produce not found'], null, $this->messages['Error'], []); }
        
        # del produces
        $delete = $sql->delete('producers')->where(['id' => $id]);
        
        try{ $producer = $adapter->query($sql->getSqlStringForSqlObject($delete), $adapter::QUERY_MODE_EXECUTE); }
        catch(\Zend\Db\Adapter\Adapter $e){ return new ApiProblem(409, $e->getPrevious()->getMessage()); }
        
        if (empty($producer)) { return true; }
        
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
        $adapter = $this->adapter; 
        $sql = new Sql($adapter);
        
        # Get produces
        $getProducer = $sql->select()
                ->from('producers')
                ->columns(['id AS producers_id', 'name AS producers_name', 'address', 'address1', 'city', 'state', 'email', 'phone', 'web'])
                ->where(['producers.id' => $id])
                ->join('countries', 'countries.id = producers.country', ['name'], Select::JOIN_INNER);
        
        try { $producer = $adapter->query($sql->getSqlStringForSqlObject($getProducer), $adapter::QUERY_MODE_EXECUTE)->toArray();}
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

        if (empty($producer)) { return new ApiProblem(404, $this->messages['Produces not found'], null, $this->messages['Error'], []); }
        
        return $producer;
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
        $getProducers = $sql->select()
                ->from('producers')
                ->columns(['id AS producers_id', 'name AS producers_name', 'address', 'address1', 'city', 'state', 'email', 'phone', 'web'])
                ->join('countries', 'countries.id = producers.country', ['name'], Select::JOIN_INNER);
        
        try { $producers = $adapter->query($sql->getSqlStringForSqlObject($getProducers), $adapter::QUERY_MODE_EXECUTE)->toArray();}
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

        if (empty($producers)) { return new ApiProblem(404, $this->messages['Produces not found'], null, $this->messages['Error'], []); }
        
        return $producers;
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
        if(!isset($id) || empty($id)){
            return new ApiProblem(412, $this->messages['Producer id must be provided'], null, $this->messages['Warning'], []);           
        }
        
        if((!isset($data->name) || empty($data->name)) ||
           (!isset($data->address) || empty($data->address)) ||
           (!isset($data->city) || empty($data->city)) ||
           (!isset($data->country) || empty($data->country)) ||
           (!isset($data->state) || empty($data->state)) ||
           (!isset($data->email) || empty($data->email)) ||
           (!isset($data->phone) || empty($data->phone))) {            
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);           
        }
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
   
        # Check if exists
        $find = $sql->select()->from('producers')->where(['id' => $id]);
        try { $producer = $adapter->query($sql->getSqlStringForSqlObject($find), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        if (empty($producer)) { return new ApiProblem(409, $this->messages['Produce do not exists'], null, $this->messages['Error'], []); }
        
        $updateProducer = $sql->update('producers')
                ->set(['name' => $data->name,
                       'address' => $data->address,
                       'address1' => isset($data->address1)? $data->address1 : "",
                       'city' => $data->city,
                       'state' => $data->state,
                       'country' => $data->country,
                       'email' => $data->email,
                       'phone' => $data->phone,
                       'web' => isset($data->web)? $data->web : "",])
                ->where(['id' => $id]);
        
        try { $producerData = $adapter->query($sql->getSqlStringForSqlObject($updateProducer), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        return new ApiProblem(200, $this->messages['Producer updated'], null, $this->messages['Success'], []);

    }
}
