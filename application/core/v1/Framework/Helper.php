<?php

/**
 * Helper
 *
 * @author Mark
 */
class Core_Framework_Helper {
    
    /**
     * Redirect to the 404 page
     * Page URL taken from config file
     * To be called statically 
     */
    public static function error404() {
        $config = Core_Framework_Singleton::config()->ini;
        Core_Framework_Helper::redirect($config['cross_site']['error404']);
    }
    
    /**
     * Simple header redirect to be called statically
     * @param string $url 
     */
    public static function redirect($url) {
        header("Location: ".$url);
    }
}

?>