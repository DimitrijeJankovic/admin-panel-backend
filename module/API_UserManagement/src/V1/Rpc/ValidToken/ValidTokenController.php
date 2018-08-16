<?php
namespace API_UserManagement\V1\Rpc\ValidToken;

use Zend\Mvc\Controller\AbstractActionController;
use ZF\ApiProblem\ApiProblem;
use ZF\Rest\AbstractResourceListener;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Application\Service\LanguageService;

class ValidTokenController extends AbstractActionController
{
    private $adapter;
    private $messages;

    public function __construct(Adapter $adapter) {

        $this->adapter = $adapter;

        # Get language
        $lang_messages = new LanguageService('EN');
        $this->messages = $lang_messages->messages;
    }
    
    public function validTokenAction()
    {
        $params = json_decode($this->getRequest()->getContent());

        # Validation
        if (!isset($params->token) || empty($params->token)) {
            return new \ZF\ApiProblem\ApiProblemResponse(
                    new \ZF\ApiProblem\ApiProblem(412, $this->messages['Token must be provided'], null, $this->messages['Error'])
            );
        }

        $adapter = $this->adapter;
        $sql = new Sql($adapter);
        
        $findToken = $sql->select()->from('temp_password')->where(['token' => $params->token]);
        try {$tokenResolt = $adapter->query($sql->getSqlStringForSqlObject($findToken), $adapter::QUERY_MODE_EXECUTE)->toArray();
        } catch (\Exception $e) { return new \ZF\ApiProblem\ApiProblemResponse(new \ZF\ApiProblem\ApiProblem(409, $e, null, $this->messages['Error'])); }
        
        if(empty($tokenResolt)){
            return new \ZF\ApiProblem\ApiProblemResponse(
                    new \ZF\ApiProblem\ApiProblem(404, $this->messages['Token not found'], null, $this->messages['Error'])
            );
        }else{
            $created= $tokenResolt[0]['created'];
            
            date_default_timezone_set('UTC');
            
            # exparation time for token in days
            $set_time = 1;

            #24h valid token
            $expiration_time = strtotime(date('Y-m-d H:i:s')) - $created;

            if ($expiration_time >= ($set_time * 24 * 60 * 60)) {
                return new \ZF\ApiProblem\ApiProblemResponse(
                    new \ZF\ApiProblem\ApiProblem(498, $this->messages['Token expired'], null, $this->messages['Error'])
                );
            }
            
        }
        
        return new \ZF\ApiProblem\ApiProblemResponse(
                    new \ZF\ApiProblem\ApiProblem(200, $this->messages['Token oke'], null, $this->messages['Success'])
            );
    }
}
