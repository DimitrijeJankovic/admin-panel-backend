<?php

namespace API_UserManagement\V1\Rest\User;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Adapter\Adapter;

class UserResource extends AbstractResourceListener {

    private $adapter;
    private $messages;
    private $language;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data) {
        if(!isset($data->first_name) || !isset($data->email)  || !isset($data->password)) {            
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);           
        }
        
        if(empty($data->first_name) || empty($data->email)  || empty($data->password)) {            
            return new ApiProblem(412, $this->messages['All fields must be provided'], null, $this->messages['Warning'], []);           
        }

        $adapter = $this->adapter;
        $sql = new Sql($adapter);
   
        # Check if user does exists in user table
        $findEmail = $sql->select()->from('oauth_clients')->where(['client_id' => $data->email]);
        
        try { $user = $adapter->query($sql->getSqlStringForSqlObject($findEmail), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        if (!empty($user)) { return new ApiProblem(409, 'User already exists', null, $this->messages['Error'], []); }
        
        $bcrypt = new Bcrypt();
        $password = $bcrypt->create($data->password);
        
        # Insert user data in table
        $createUser = $sql->insert('oauth_clients')->values([
            'first_name' => $data->first_name,
            'last_name' => isset($data->last_name) ? $data->last_name : "",
            "username" => !empty($data->username) ? $data->username : "",
            'client_secret' => $password,
            'client_id' => $data->email,
            'user_id' => 1,
            'role' => 2,
            "redirect_uri" => "",
            "status" => 0
        ]);
        
        try { $userData = $adapter->query($sql->getSqlStringForSqlObject($createUser), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        # Update user_id column in newly created user data - this is done because we will be able to get userId directly through apigiliy
        $updateUserId = $sql->update('oauth_clients')
                ->set(['user_id' => $userData->getGeneratedValue()])
                ->where(['id' => $userData->getGeneratedValue()]); 
        
        try { $update = $adapter->query($sql->getSqlStringForSqlObject($updateUserId), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        if($update){
            return new ApiProblem(200, 'User created', null, $this->messages['Success'], []);
        }
    }

    /**
     * Delete a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function delete($id) {
        return new ApiProblem(405, 'The DELETE method has not been defined for individual resources');
    }

    /**
     * Delete a collection, or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function deleteList($data) {
        return new ApiProblem(405, 'The DELETE method has not been defined for collections');
    }

    /**
     * Fetch a resource
     *
     * @param  mixed $id
     * @return ApiProblem|mixed
     */
    public function fetch($id) {
        return new ApiProblem(405, 'The GET method has not been defined for individual resources');
    }

    /**
     * Fetch all or a subset of resources
     *
     * @param  array $params
     * @return ApiProblem|mixed
     */
    public function fetchAll($params = []) {
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        $getUsers = $sql->select()->from('oauth_clients');
        
        try { $users = $adapter->query($sql->getSqlStringForSqlObject($getUsers), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        return $users;
        
    }

    /**
     * Patch (partial in-place update) a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patch($id, $data) {
        return new ApiProblem(405, 'The PATCH method has not been defined for individual resources');
    }

    /**
     * Patch (partial in-place update) a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function patchList($data) {
        return new ApiProblem(405, 'The PATCH method has not been defined for collections');
    }

    /**
     * Replace a collection or members of a collection
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function replaceList($data) {
        return new ApiProblem(405, 'The PUT method has not been defined for collections');
    }

    /**
     * Update a resource
     *
     * @param  mixed $id
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function update($id, $data) {
        return new ApiProblem(405, 'The PUT method has not been defined for individual resources');
    }

}
