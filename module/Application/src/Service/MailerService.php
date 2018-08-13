<?php
namespace Application\Service;		

use Zend\Mail;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Application\Utility;

class MailerService 
{
    public $options;
    public $email_from;
    public $name_from;
    
    public function __construct() {
        $this->options = new SmtpOptions(array(
            'name' => 'captn.de',
            'host' => 'alfa3062.alfahosting-server.de',
            'port'=> 465,
            'connection_class' => 'login',
            'connection_config' => array(
                'username' => 'web67p12',
                'password' => 'gemKI8SO',
                'ssl'=> 'ssl',
            ),
        ));
        //$this->email_from = 'info@captain.com';
        //$this->name_from = 'Captain';
        $this->email_from = 'app@captn.de';
        $this->name_from = 'Admin Panel';
    }
    
    public function resetPasswordMail($user_email, $token, $messages) {
        $msgbodyInsert = \Application\Model\Config::ROOT_URL."/mail_service/password_reset.php?token=".$token;
        
        $content = $messages['reset_part1'] .
                "<br><br><a href='".$msgbodyInsert."'>".$msgbodyInsert."</a>";   
        
        // make a header as html
        $html = new MimePart($content);
        $html->type = "text/html";
        $html->charset = 'UTF-8';
        $body = new MimeMessage();
        $body->setParts(array($html,));

        // instance mail 
        $mail = new Mail\Message();
        $mail->setEncoding("UTF-8");
        $mail->setBody($body);
        $mail->setFrom($this->email_from, $this->name_from);
        $mail->setTo($user_email);
        $mail->setSubject($messages['reset_subject']);
        $transport = new SmtpTransport($this->options);
        $transport->send($mail); 
        
        return true;
    }
        
}
