<?php

use ZF\Rest\AbstractResourceListener;
use Application\Service\LanguageService;

include '../../module/Application/src/Model/Config.php';
include '../../module/Application/src/Utility/TextUtility.php';
include '../../src/Backend/Helpers/LanguageMessages.php';

require '../../src/Backend/Helpers/CryptoOperations.php';

$messageProxy = new \Backend\Helpers\LanguageMessages();

class ResetPassword {
    private $localMessageProxy;

    public function __construct($msgProxy) {
        $this->localMessageProxy = $msgProxy;
    }

    public function alert($message) {
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

    public function codeExists() {
        if (isset($_GET['code'])) {
            $code = htmlspecialchars($_GET['code']);
        } else {
            $this->alert($this->localMessageProxy->getMessage("Code missing"));
            die;
        }

        $Data = include "../../config/autoload/local.php";

        #conect to database 
        $creds = [
            'host' => $Data['db']['host'],
            'user' => $Data['db']['username'],
            'password' => $Data['db']['password'],
            'database' => $Data['db']['adapters']['be_db']['database'],
            'url' => $Data['db']['url']
        ];

        $conn = new mysqli($creds['host'], $creds['user'], $creds['password'], $creds['database']);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM `temp_user` WHERE registration_code = " . mysqli_real_escape_string($conn, $code) . ";";
        $result = $conn->query($sql);

        if (!$result) {
            $this->alert($this->localMessageProxy->getMessage("Activation_Code_NotValid"));
            die;
        }

        if ($result->num_rows == 0) {
            $this->alert($this->localMessageProxy->getMessage("Activation_Code_Expired"));
            die;
        }

        $user = mysqli_fetch_assoc($result);

        # if code of temp user exists conect to endpoint to validate user, 
        # endpoint crates user in oauth_clients tabel
        # endpoint is /user POST 


        $Data = include "../../config/autoload/local.php";

        #login users
        //if ($Data['db']['env'] == 'development') {
            //$ch_crate = \curl_init("http://captn-local.com:444/user");
        //} else {
            //$ch_crate = \curl_init("https://captn-dev.appsolute.de:8888/user");
        //}
        
        $urlCurl = \Application\Utility\TextUtility::ClearUrl(\Application\Model\Config::ROOT_URL."/user");

        $ch_crate = \curl_init($urlCurl);
        
        //$decrypt_pass = \Backend\Helpers\CryptoOperations::decryptBase64($user['client_secret']);
        
        //$decrypt_pass = str_replace("\0", "", $decrypt_pass);
        
        $decrypt_pass = $user['client_secret'];

        $password = $decrypt_pass;//$data->password;

        $daily_coins = '{"date":' . time() . ',"coins":' . \Application\Model\Config::DEFAULT_DAILY_COINS  . '}';

        $mp_fn = $user['first_name'];
        $mp_ln = (isset($user['last_name']) ? $user['last_name'] : "");
        $mp_pass = $password;
        $mp_clientid = $user['client_id'];
        $mp_usr = 1;
        $mp_role = 2;
        $mp_coins =  \Application\Model\Config::DEFAULT_COINS_FOR_NEW_USERS;
        $mp_boosters = \Application\Model\Config::DEFAULT_BOOSTERS_FOR_NEW_USERS;
        $mp_dailycoins = $daily_coins;
        
        $userOld = null;
        
        $stmt = $conn->prepare("SELECT client_id FROM oauth_clients WHERE client_id = ?");// (firstname, lastname, email) VALUES (?, ?, ?)");
        $stmt->bind_param("s", $mp_clientid );
        $mp_useract = $user['client_id'];
        $stmt->execute();

        $userOld = $stmt->get_result();
        
        if ($userOld->num_rows > 0) {           
            $this->alert($this->localMessageProxy->getMessage("Activation_Code_Error"));
            die;
        }
        
        $stmt->close();

        $stmt = $conn->prepare("INSERT INTO oauth_clients (first_name, last_name, client_secret, client_id, user_id, role, coins, boosters, daily_coins) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiiiss", $mp_fn, $mp_ln, $mp_pass, $mp_clientid, $mp_usr, $mp_role, $mp_coins, $mp_boosters, $mp_dailycoins  );
        $stmt->execute();
        //$stmt->bind_result($userData);
        
        $newId = $stmt->insert_id;
        
        $stmt->close();

        $dateData = date("Y-m-d H:i:s");
        $ker = 100;
        $voli = 1;
        $mcc = "{}";
        
        $stmt = $conn->prepare("INSERT INTO coin_transaction (date, amount, type, user_id, metadata) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siiis", $dateData, $ker, $voli, $newId, $mcc );
        $stmt->execute();

        $stmt->close();
        
        $stmt = $conn->prepare("UPDATE oauth_clients SET user_id=? WHERE id=?");
        $stmt->bind_param("si", $newId, $newId);
        $stmt->execute();
        
        $stmt->close();
        
        
        $stmt = $conn->prepare("DELETE FROM temp_user WHERE registration_code =?");
        $stmt->bind_param("i", $code);
        $stmt->execute();
        
        $stmt->execute();

        
        //$cleaned = mysqli_real_escape_string($conn, $code);
        //$sql = "DELETE FROM temp_user WHERE registration_code = " . mysqli_real_escape_string($conn, $code) . ";";
        //$result = $conn->query($sql);
        
/*
        if ($response_create[2] == '"status":200') {
            # delate temp user
            $sql = "DELETE FROM temp_user WHERE registration_code = " . mysqli_real_escape_string($conn, $code) . ";";
            $result = $conn->query($sql);
        } else {
            $this->alert($this->localMessageProxy->getMessage("Activation_Code_Error"));
            die;
        }
 */
 
    }

}
header('Content-Type: text/html; charset=utf-8');
$test = new ResetPassword($messageProxy);
$test->codeExists();

?>


<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $messageProxy->getMessage("welcome_subject"); ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="images/icon.png"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="animate/animate.css">
        <link rel="stylesheet" type="text/css" href="css-hamburgers/hamburgers.min.css">
        <link rel="stylesheet" type="text/css" href="select2/select2.min.css">
        <link rel="stylesheet" type="text/css" href="css/util.css">
        <link rel="stylesheet" type="text/css" href="css/main.css">
    </head>
    <body>

        <div class="bg-contact2" style="background-image: url('');">
            <div class="container-contact2">
                <div class="wrap-contact2">
                    <div class="row header">
                        <div class="column2">
                            <img src="images/captn-app-icon-l.png">
                        </div>
                      </div> 
                    <span class="contact2-form-title"><?php echo $messageProxy->getMessage("Account_New_Activated"); ?> </span>
                </div>
            </div>
        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
        <script src="select2/select2.min.js"></script>
        <script src="js/main.js"></script>
        <script>

            $(document).ready(function(){
                var ua = navigator.userAgent.toLowerCase(); 
                if (ua.indexOf('safari') != -1) { 
                  if (ua.indexOf('chrome') > -1) {
                    //alert("1") // Chrome
                    console.log('No redirection to captn login');
                  } else {
                    //alert("2") // Safari
                    //redirect
                    console.log('Redirection to captn login');
                    window.location.replace("captn://login");
                    
                  }
                }
                else
                {
                    console.log('No redirection to captn login');
                }
            });

        </script>

    </body>
</html>
