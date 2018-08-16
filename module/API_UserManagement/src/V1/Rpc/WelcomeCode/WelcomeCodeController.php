<?php
namespace API_UserManagement\V1\Rpc\WelcomeCode;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;

class WelcomeCodeController extends AbstractActionController
{
    
    private $adapter;
    private $messages;

    public function __construct(Adapter $adapter) {

        $this->adapter = $adapter;

        # Get language
        $lang_messages = new LanguageService('EN');
        $this->messages = $lang_messages->messages;
    }
    
    public function welcomeCodeAction()
    {
        
        $code = $_GET['code'];
        
        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        if(empty($code) || !isset($code)){
            return new \ZF\ApiProblem\ApiProblemResponse(
                    new \ZF\ApiProblem\ApiProblem(409, $this->messages['Activation code needed'], null, $this->messages['Error'])
            );
        }
        
        // get user
        $getUser = $sql->select()->from('temp_user')->where(['registration_code' => $code]);
        
        try { $user = $adapter->query($sql->getSqlStringForSqlObject($getUser), $adapter::QUERY_MODE_EXECUTE)->toArray(); }
        catch (\Zend\Db\Adapter\Adapter $e) { return new ApiProblem(409, $e->getPrevious()->getMessage(), null, $this->messages['Error'], []); }
        
        if(empty($user)){
            return new \ZF\ApiProblem\ApiProblemResponse(
                    new \ZF\ApiProblem\ApiProblem(409, $this->messages['User does not exist'], null, $this->messages['Error'])
            );
        }
        
        #Begin transaction
        $adapter->getDriver()->getConnection()->beginTransaction();
        
        # Insert user data in table
        $createUser = $sql->insert('oauth_clients')->values([
            'first_name' => $user[0]['first_name'],
            'last_name' => $user[0]['last_name'],
            "username" => "",
            'client_secret' => $user[0]['client_secret'],
            'client_id' => $user[0]['client_id'],
            'user_id' => 1,
            'role' => 2,
            "redirect_uri" => "",
            "status" => 0
        ]);
       $userData = $adapter->query($sql->getSqlStringForSqlObject($createUser), $adapter::QUERY_MODE_EXECUTE); 
        
        # Update user_id column in newly created user data - this is done because we will be able to get userId directly through apigiliy
        $updateUserId = $sql->update('oauth_clients')
                ->set(['user_id' => $userData->getGeneratedValue()])
                ->where(['id' => $userData->getGeneratedValue()]); 
        $update = $adapter->query($sql->getSqlStringForSqlObject($updateUserId), $adapter::QUERY_MODE_EXECUTE);
        
        // remove temp user
        $deleteTempUser = $sql->delete('temp_user')->where(['registration_code' => $code]);
        $adapter->query($sql->getSqlStringForSqlObject($deleteTempUser), $adapter::QUERY_MODE_EXECUTE); 
        
        #Commit transaction
        $adapter->getDriver()->getConnection()->commit();
        
        return new \ZF\ApiProblem\ApiProblemResponse(
                    new \ZF\ApiProblem\ApiProblem(200, $this->messages['User created'], null, $this->messages['Success'])
            );
        
    }
}
