<?php

use ZF\Rest\AbstractResourceListener;
use Application\Service\LanguageService;
use Application\Utility;

include '../../module/Application/src/Model/Config.php';
include '../../module/Application/src/Utility/TextUtility.php';
include '../../src/Backend/Helpers/LanguageMessages.php';

$messageProxy = new \Backend\Helpers\LanguageMessages();

$url = \Application\Model\Config::ROOT_URL . 'mail_service/pass_not_valid.html';

$new_pass = htmlspecialchars($_REQUEST["newPass"]);
$conf_pass = htmlspecialchars($_REQUEST["confPass"]);
$token = htmlspecialchars($_REQUEST['token']);

if (isset($_REQUEST['newPass']) && isset($_REQUEST['confPass']) && isset($_REQUEST['token'])) {

    if (empty($token)) {
        print $messageProxy->getMessage("Access token must be provided");
        ;
        exit();
    }

    if (empty($new_pass) and empty($conf_pass)) {
        print $messageProxy->getMessage("New password must be provided"); //"Passwords can not be empty.";
        exit();
    }

    if ($new_pass != $conf_pass) {
        print $messageProxy->getMessage("Password change failed. Old password did not match"); //"Passwords do not match.";
        exit();
    }

# pass must be at least 1 chars long, must have at least one capital letter an one number
    if (strlen($new_pass) < 1) {
        print $messageProxy->getMessage("Reset_Password_AtLeast1"); //Password must contain at least eight characters.";
        exit();
    }
//elseif (!preg_match("#[0-9]+#", $new_pass)) {
//print $messageProxy->getMessage("Reset_Password_OneNumber");//"Password must contain at least one number.";
//exit();
//} elseif (!preg_match("#[A-Z]+#", $new_pass)) {
//print $messageProxy->getMessage("Reset_Password_Uppercase");// "Password must contain at least one uppercase letter.";
//exit();
//} elseif (!preg_match("#[a-z]+#", $new_pass)) {
//print $messageProxy->getMessage("Reset_Password_Lowercase");// "Password must contain at least one lowercase letter.";
//exit();
//}
//$Data = include "../../config/autoload/local.php";

    $urlCurl = Utility\TextUtility::ClearUrl(\Application\Model\Config::ROOT_URL . "/forgot-password");
//
#login users
//if ($Data['db']['env'] == 'development') {
//$urlCurl = \curl_init(\Application\Model\Config::ROOT_URL."/reset");
//$urlCurl = \Application\Model\Config::ROOT_URL."reset";
//} else {
//$urlCurl = \curl_init(\Application\Model\Config::ROOT_URL."reset");
//$urlCurl = \Application\Model\Config::ROOT_URL."reset";
//}
//print($urlCurl);

    $ch_reset = \curl_init($urlCurl);

    $json = [
        "reset" => [
            "token" => $token,
            "newPassword" => $new_pass
        ]
    ];

    $headers_reset = array();
    $headers_reset[] = 'Content-Type: application/json';
    $headers_reset[] = 'Accept: application/json';

//Setup curl, add headers and post parameters.
    \curl_setopt($ch_reset, CURLOPT_CUSTOMREQUEST, "PUT");
    \curl_setopt($ch_reset, CURLOPT_POSTFIELDS, json_encode($json));
    \curl_setopt($ch_reset, CURLOPT_HTTPHEADER, $headers_reset);
    \curl_setopt($ch_reset, CURLOPT_RETURNTRANSFER, true);

//Send the request
    $response_reset = \curl_exec($ch_reset);

    $response_reset = explode(",", $response_reset);


    if ($response_reset[2] == '"status":200') {
        print("OK");
    } else {
        print($messageProxy->getMessage("Reset_Password_Error")); // "Error in password change.");
    }
}else{
    var_dump('files needed');
}
