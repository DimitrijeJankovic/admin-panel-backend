<?php

namespace API_UserManagement\V1\Rest\User;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Crypt\Password\Bcrypt;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;
use Application\Service\MailerService;

class UserResource extends AbstractResourceListener {

    private $adapter;
    private $messages;
    
    public function __construct(Adapter $adapter) {
        
        $this->adapter = $adapter;
        
        # Get language       
        $language = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? strtoupper($_SERVER['HTTP_ACCEPT_LANGUAGE']) : "EN";
        $lang_messages = new LanguageService($language);
        $this->messages = $lang_messages->messages;
    }
    
    private function generateNumber($digits){
        return str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
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
        
        if (!empty($user)) { return new ApiProblem(409, $this->messages['User already exists'], null, $this->messages['Error'], []); }
        
        # Check if user does exists in temp_user table
        $findEmail = $sql->select()->from('temp_user')->where(['client_id' => $data->email]);
        try { $user = $adapter->query($sql->getSqlStringForSqlObject($findEmail), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        if (!empty($user)) { return new ApiProblem(409, $this->messages['User already exists'], null, $this->messages['Error'], []); }
        
        $bcrypt = new Bcrypt();
        $password = $bcrypt->create($data->password);
        
        $activation_code = $this->generateNumber(6);
        
        # Insert user data in table
        $createUser = $sql->insert('temp_user')->values([
            'first_name' => $data->first_name,
            'last_name' => isset($data->last_name) ? $data->last_name : "",
            'client_secret' => $password,
            'client_id' => $data->email,
            'registration_code' => $activation_code
        ]);
        
        try { $userData = $adapter->query($sql->getSqlStringForSqlObject($createUser), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        # send confer mail
        $mailer = new MailerService();
        $mailer->welcomeMail($data->email, $activation_code, $this->messages);
        
        return [];
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
