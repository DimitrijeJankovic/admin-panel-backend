<?php
namespace API_UserManagement\V1\Rest\ForgotPassword;

use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Zend\Crypt\Password\Bcrypt;

use Application\Service\LanguageService;
use Application\Service\MailerService;

class ForgotPasswordResource extends AbstractResourceListener
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
    
    // token generator used for new password 
    private function generateToken($num)
    {
        $chars = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $token = [];
        $charsLength = strlen($chars) - 1; // put the length -1 in cache
        for ($i = 0; $i < $num; $i ++) {
            $n = rand(0, $charsLength);
            $token[] = $chars[$n];
        }
        $token = implode($token);
        return $token;
    }
    
    private function generateNumber($digits)
    {
        return str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
    }

    /**
     * Create a resource
     *
     * @param  mixed $data
     * @return ApiProblem|mixed
     */
    public function create($data)
    {
        if(!isset($data->email)) { return new ApiProblem(412, $this->messages['Email must be provided'], null, $this->messages['Warning'], []); }
        
        $adapter = $this->adapter; 
        $sql = new Sql($adapter);
        
        # Get user
        $getUser = $sql->select()->from('oauth_clients')->where(array('client_id' => $data->email));
            
        try { $user = $adapter->query($sql->getSqlStringForSqlObject($getUser), $adapter::QUERY_MODE_EXECUTE)->toArray();}
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

        if (empty($user)) { return new ApiProblem(404, $this->messages['User does not exist'], null, $this->messages['Error'], []); }

        $token = $this->generateToken(24);
        
        # Insert temp password
        $insert = $sql->insert('temp_password')->values([
            'user_id' => $user[0]['id'], 
            'token'  => $token,
            'created'  => time(),
       ]);
            
        try { $adapter->query($sql->getSqlStringForSqlObject($insert), $adapter::QUERY_MODE_EXECUTE); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage()); }

        # Send reset email to user
        $mailer = new MailerService();
        $mailer->resetPasswordMail($user[0]['client_id'], $token, $this->messages);
        
        return new ApiProblem(200, "Email has been sent" , null, $this->messages['Success'], []);
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
        return new ApiProblem(405, 'The GET method has not been defined for collections');
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
        if(!$data['reset']->token || !$data['reset']->newPassword){
            return new ApiProblem(412, $this->messages['All fields must be provided'] , null, $this->messages['Warning'], []);
        }
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        $token = $data['reset']->token;
        $newPassword = $data['reset']->newPassword;
        
        $getToken = $sql->select()->from('temp_password')
                ->join(['oc' => 'oauth_clients'], 'oc.id = temp_password.user_id', ['client_id'], 'left')
                ->where(['temp_password.token' => $token]);
            
        try{ $tempPass = $adapter->query($sql->getSqlStringForSqlObject($getToken), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch(\Zend\Db\Adapter\Adapter $e){ return new ApiProblem(409, $e->getPrevious()->getMessage(), null, 'Error', []); }

        if(empty($tempPass)) {
            return new ApiProblem(404, $this->messages['Password reset must be requested'], null, $this->messages['Error'], []);
        }
        
        $bcrypt = new Bcrypt();
        
        #Update password
        $new_pass = $bcrypt->create($newPassword);        
        $updatePass = $sql->update('oauth_clients')
            ->set(['client_secret' => $new_pass])
            ->where(['id' => $tempPass[0]['user_id']]);
        try{ $adapter->query($sql->getSqlStringForSqlObject($updatePass), $adapter::QUERY_MODE_EXECUTE); }
        catch(\Zend\Db\Adapter\Adapter $e){ return new ApiProblem(409, $e->getPrevious()->getMessage(), null, 'Error', []); }
        
        # Delete temp password
        $deleteTempPass = $sql->delete()->from('temp_password')->where(['token' => $token]);            
        try{ $adapter->query($sql->getSqlStringForSqlObject($deleteTempPass), $adapter::QUERY_MODE_EXECUTE); }
        catch(\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
           
        # Delete old access token
        $deleteAccessToken = $sql->delete('oauth_access_tokens')->where(['client_id' => $tempPass[0]['client_id']]);
        try{ $adapter->query($sql->getSqlStringForSqlObject($deleteAccessToken), $adapter::QUERY_MODE_EXECUTE); }
        catch(\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        return new ApiProblem(200, $this->messages['Password has been changed'], null, $this->messages['Success'], []);
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
