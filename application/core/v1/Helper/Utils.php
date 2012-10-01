<?php

/**
 * A few useful utilities
 *
 * @author Mark
 */
class Core_Helper_Utils {

    public static function getPublicObjectVars($object) {
        $pobj = get_object_vars($object);
        return $pobj;
    }

    public function makeidsafe($string) {
        $string = preg_replace(array('/ /', '/\'/', '/"/'), array('-', '', ''), $string);
        return $string;
    }

    //General Generate Functions   
    public function GUID() {
        $varGUID = str_replace('.', '', uniqid($_SERVER['REMOTE_ADDR'], TRUE)) . '-' . rand(0, 9);
        return $varGUID;
    }

    public function UTC() {
        return date("Y-m-d\TH:i:s\Z");
    }

    public function DateStamp() {
        return date("Y-m-d H:i:s");
    }

    /**
     * Simple header redirect to be called statically
     * @param string $url 
     */
    public static function redirect($url) {
        Core_Framework_Helper::redirect($url);
    }

}

?>
