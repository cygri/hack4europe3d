<?php

class Core_Helper_Sanitize {

    public static function html($string) {
        if (is_object($string)) {
            $r = $string;
            foreach ($string as $k => $v) {
                $r->$k = Core_Helper_Sanitize::html($v);
            }
        } elseif (is_array($string)) {
            $r = $string;
            foreach ($string as $k => $v) {
                $r[$k] = Core_Helper_Sanitize::html($v);
            }
        } else {
            $r = htmlentities($string, ENT_QUOTES, "UTF-8", true);
        }
        return $r;
    }

    public static function db($string) {
        $r = mysql_real_escape_string($string);
        return $r;
    }

    public static function lowHtml($string) {
        $r = htmlentities($string);
        return $r;
    }

    public static function reverseHtml($string) {
        if (is_object($string)) {
            $r = $string;
            foreach ($string as $k => $v) {
                $r->$k = Core_Helper_Sanitize::reverseHtml($v);
            }
        } elseif (is_array($string)) {
            $r = $string;
            foreach ($string as $k => $v) {
                $r[$k] = Core_Helper_Sanitize::reverseHtml($v);
            }
        } else {
            $r = html_entity_decode($string, ENT_QUOTES, "UTF-8");
        }
        return $r;
    }

}

?>