<?php
namespace Application\Utility;		

/**
 * Various methods for text operations 
 *
 * @author Nebojsa Tomcic
 */
class TextUtility {

    static private function ClearString($instring, $section)
    {
            $instring = substr( $instring, $section );
            $instring = str_replace("/////", "/", $instring);
            $instring = str_replace("////", "/", $instring);
            $instring = str_replace("///", "/", $instring);
            $instring = str_replace("//", "/", $instring);
            return $instring;
    }

    static public function ClearUrl($string_n)
    {
            $prefix = "";

            if(substr( $string_n, 0, 7 ) === "http://")
            {
                $prefix = substr( $string_n, 0, 7 );
                $newstring = self::ClearString($string_n, 7);
                $string_n = $prefix . $newstring;

            }
            else if(substr( $string_n, 0, 8 ) === "https://")
            {
                $prefix = substr( $string_n, 0, 8 );
                $newstring = self::ClearString($string_n, 8);
                $string_n = $prefix . $newstring;
            }
            else
            {  
                //clear other strings
                $string_n = self::ClearString($string_n, 0);
            }

            return $string_n;
    }

}
