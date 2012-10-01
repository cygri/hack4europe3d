<?php

/**
 * Singleton Manager
 * Add any and start using
 * @author Mark
 */
class Core_Framework_Singleton {

    private static $Auth;
    private static $Config;

    /** @var Core_Framework_View $View * */
    private static $View;
    private static $DB;
    private static $store;
    private static $Intact;

    private function __construct() {
        
    }

    /**
     * Returns the config file array
     * @return Core_Framework_Config 
     */
    public static function config() {
        if (!isset(self::$Config)) {
            self::$Config = new Core_Framework_Config();
        }
        return self::$Config;
    }

    /**
     * Returns the config file array
     * @return Core_Framework_Auth 
     */
    public static function auth() {
        if (!isset(self::$Auth)) {
            self::$Auth = new Core_Framework_Auth();
        }
        return self::$Auth;
    }

    /**
     * Singleton of view
     * @return Core_Framework_View 
     */
    public static function view() {
        if (!isset(self::$View)) {
            self::$View = new Core_Framework_View();
        }
        return self::$View;
    }

    /**
     * Singleton of DB
     * @return Core_Framework_DB 
     */
    public static function db() {
        if (!isset(self::$DB)) {
            self::$DB = new Core_Framework_DB();
        }
        return self::$DB;
    }

    /**
     * Singleton quick store for items
     * @return Core_Framework_Store
     */
    public static function store() {
        if (!isset(self::$store)) {
            self::$store = new Core_Framework_Store();
        }
        return self::$store;
    }

    /**
     * Singleton of DB
     * @return Core_Intact_SOAP 
     */
    public static function intact() {
        if (!isset(self::$Intact)) {
            self::$Intact = new Core_Intact_SOAP();
        }
        return self::$Intact;
    }

    public function __clone() {
        trigger_error('Clone is not allowed.', E_USER_ERROR);
    }

    public function __wakeup() {
        trigger_error('Unserializing is not allowed.', E_USER_ERROR);
    }

}

?>