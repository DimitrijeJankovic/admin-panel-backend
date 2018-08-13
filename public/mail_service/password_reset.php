<?php

use ZF\Rest\AbstractResourceListener;
use Application\Service\LanguageService;

include '../../module/Application/src/Model/Config.php';
include '../../src/Backend/Helpers/LanguageMessages.php';

$messageProxy = new \Backend\Helpers\LanguageMessages();

class ResetPassword {

    private $localMessageProxy;

    public function __construct($msgProxy) {
        $this->localMessageProxy = $msgProxy;
    }

    public function alert($message) {
        echo "<script type='text/javascript'>alert('$message');</script>";
    }

    public function tokenExists() {

        $url = \Application\Model\Config::ROOT_URL . '/mail_service/link_expired.php';

        if (isset($_GET['token'])) {
            $token = htmlspecialchars($_GET['token']);
        } else {
            $this->alert($this->localMessageProxy->getMessage("Access token must be provided"));
            die;
        }
        
        $Data = include "../../config/autoload/local.php";

        #conect to database 
        $creds = [
            'host' => $Data['db']['host'],
            'user' => $Data['db']['username'],
            'password' => $Data['db']['password'],
            'database' => $Data['db']['adapters']['admin_panel_db']['database'],
            'url' => $Data['db']['url']
        ];

        $conn = new mysqli($creds['host'], $creds['user'], $creds['password'], $creds['database']);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM temp_password WHERE token = '" . mysqli_real_escape_string($conn, $token) . "';";
        $result = $conn->query($sql)->fetch_object();
        
        # exparation time for token in days
        $set_time = 1;

        $token_crated = $result->created;

        date_default_timezone_set('UTC');

        #24h valid token
        $expiration_time = strtotime(date('Y-m-d H:i:s')) - $token_crated;

        if ($expiration_time >= ($set_time * 24 * 60 * 60)) {
            header("Location: " . $url);
            exit();
        }

        if ($result == null or empty($result)) {
            header("Location: " . $url);
            exit();
        }
        
    }

}

$test = new ResetPassword($messageProxy);
$test->tokenExists();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title><?php echo $messageProxy->getMessage("Password_Reset_Title"); ?></title>
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

        <div class="bg-contact2">
            <div class="container-contact2">
                <div class="wrap-contact2">

                    <form name="form" id="form" class="contact2-form validate-form">

                        <h1 class="contact2-form-title"> <?php echo $messageProxy->getMessage("Password_Reset_Title"); ?> </h1>
                        <h3 class="contact2-form-sec1-title"> <?php echo $messageProxy->getMessage("Password_Reset_Sec1_Title"); ?> </h3>

                        <div class="form-group">
                            <input id="newPass" name="newPass" type="password" class="input-feral form-control" placeholder="<?php echo $messageProxy->getMessage("Password_Reset_NewPassword"); ?>">
                        </div>
                        
                        <div class="form-group input-feral2">
                            <input id="confPass" name="confPass" type="password" class="input-feral form-control" placeholder="<?php echo $messageProxy->getMessage("Password_Reset_Confirm"); ?>">
                        </div>

                        <div class="save-btn">
                            <button type="button" name="submit" id="submit">
                                <?php echo $messageProxy->getMessage("Password_Reset_Update"); ?>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
        <script src="select2/select2.min.js"></script>
        <script src="js/main.js"></script>
        <script src="js/validationcustom.js"></script>

    </body>
</html>
