<?php

abstract class Core_Framework_Action {

    /**
     * View Singleton
     * @var Core_Framework_View 
     */
    public $view;

    /**
     * Session data
     * @var Zend_Session_Namespace 
     */
    public $session;

    function __construct() {
        $this->view = Core_Framework_Singleton::view();
        $this->session = Core_Framework_Singleton::auth()->session;
    }

    function pre() {
        
    }

    function init() {
        
    }

    function post() {
        
    }

    
    /*
     * Flood Prevention
     */
    
    function prevent_flood() {
        list($hash, $time) = explode('-', $_GET['code']);
        $check = Core_Helper_Encrypt::hash(Core_Framework_Singleton::config()->ini['web_shop']['secret'] . '-' . $time);
        if ($check == $hash) {
            if ((time() - $time) > 20) {
                Core_Helper_Utils::redirect("/system/intact/expired.html");
            }
        } else {
            Core_Helper_Utils::redirect("/system/intact/reject.html");
        }
    }

    static function flood_code() {
        $secret = Core_Framework_Singleton::config()->ini['web_shop']['secret'];
        $time = time();
        $check = Core_Helper_Encrypt::hash($secret . '-' . $time);

        return $check . '-' . $time;
    }

}

?>