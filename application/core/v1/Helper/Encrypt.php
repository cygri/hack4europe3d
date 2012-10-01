<?php

class Core_Helper_Encrypt {
    static function hash($string) {
        $process = hash('sha512', $string);
        return $process;
    }
}