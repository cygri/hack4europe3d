<?php

/**
 * Description of DB
 *
 * @author Mark
 */
class Core_Framework_DB {
    public $connection;
    
    public function __construct() {
        $config = Core_Framework_Singleton::config()->ini['database'];
        $connection = mysql_connect($config['server'], $config['username'], $config['password']);
        mysql_select_db($config['database'], $connection);
        $this->connection = $connection;
        return $connection;
    }
}

?>