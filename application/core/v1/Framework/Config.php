<?php

/**
 * Config settings pulled from the relevant ini file.
 *
 * @author Mark
 */
class Core_Framework_Config {
    
    /**
     * INI Config array
     * @var array 
     */
    public $ini;
    
    public $currency_symbol = '&euro;';
    
    function attach($ini) {
        $this->ini = $ini;
        
        foreach($this->ini['defined'] as $key => $v) {
            define(strtoupper($key), $v);
        }
    }
    
    function __toString() {
        return $this->ini;
    }
}

?>