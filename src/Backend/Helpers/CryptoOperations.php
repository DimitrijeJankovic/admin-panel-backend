<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Backend\Helpers;

/**
 * Description of CryptoOperations
 *
 * @author Nebojsa Tomcic
 */
class CryptoOperations {
    //put your code here  
    static private $ENCRYPTION_KEYVAL = "@32#78$@y33fd3dp@!s9h2387@t2542724g$";


    /**
     * Returns an Base64 encrypted & utf8-encoded 
     */
    static public function encryptBase64($pure_string) {
        $encryptedBase64_string = base64_encode(self::encrypt($pure_string));
        return $encryptedBase64_string;
    }

    /**
     * Returns decrypted original from Base64 string
     */
    static public function decryptBase64($encrypted_string) {
        $decrypted_string = self::decrypt(base64_decode($encrypted_string));
        return $decrypted_string;
    }    
    
    /**
     * Returns an encrypted & utf8-encoded
     */
    static public function encrypt($pure_string) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, self::$ENCRYPTION_KEYVAL, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
        return $encrypted_string;
    }

    /**
     * Returns decrypted original string
     */
    static public function decrypt($encrypted_string) {
        $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, self::$ENCRYPTION_KEYVAL, $encrypted_string, MCRYPT_MODE_ECB, $iv);
        return $decrypted_string;
    }
}
