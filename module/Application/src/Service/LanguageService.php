<?php
namespace Application\Service;		


class LanguageService 
{
    public $messages;
    
    public function __construct($language) {
        if($language != 'DE'  && $language != 'EN' && $language != 'FR') {
            
//            $response = array(
//                "type" => "",
//                "title" => "Error",
//                "status" => 409,
//                "detail" => "Language must be provided"
//            );
//            echo json_encode($response);die;
            $language = "EN";
        }

        # Get messages according to language
        $messages = file_get_contents(\Application\Model\Config::LANG_FOLDER."/".$language.".json");
        $this->messages = json_decode($messages, true);
        
        return $this->messages;
    }
        
}
