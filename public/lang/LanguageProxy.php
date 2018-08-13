<?php
include '../../module/Application/src/Model/Config.php';
include '../../src/Backend/Helpers/LanguageMessages.php';


$messageProxy = new \Backend\Helpers\LanguageMessages();

//echo $messageProxy->getLanguage();

$file = \Application\Model\Config::LANG_FOLDER . "/" . $messageProxy->getLanguage() .".json";

//echo $file;

$data = file_get_contents($file);
echo $data;
