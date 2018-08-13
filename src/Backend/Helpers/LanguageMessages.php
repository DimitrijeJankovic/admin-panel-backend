<?php
namespace Backend\Helpers;		

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of LanguageMessages
 *
 * @author Nebojsa Tomcic
 */
class LanguageMessages {
    private $messages;
    private $language;

    public function __construct() {
        $this->language = $this->GetLangaugeFromClient();
        # Get messages according to language
        $messages = file_get_contents(\Application\Model\Config::LANG_FOLDER."/".$this->language.".json");
        $this->messages = json_decode($messages, true);
    }
    
    private function GetLangaugeFromClient()
    {
            $client = "EN"; //default langauge
            
            if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
            {
                $client = strtoupper($_SERVER['HTTP_ACCEPT_LANGUAGE']);    
            }

            if(strlen($client)  < 2)
                return "EN";

            $lang = substr($client, 0, 2);

            return $this->GetLangaugefromcode($lang);
    }


    private function GetLangaugefromcode($langcode)
    {
            switch ($langcode){
                case "DE":
                    return "DE";
                //case "FR":
                    //return "FR";
                default:
            }

            return "EN";
    }
    
    public function getLanguage()
    {
        return $this->language;
    }
    
    public function getAllMessages()
    {
        return $this->messages;
    }
    
    public function getMessage($single)
    {
        if (array_key_exists($single, $this->messages)) {
            return $this->messages[$single];        
        }
        
        return "********* NOT_DEFINED ***********";
    }
    
    
}


?>