<?php

class Core_Helper_Validation {

    /**
     * Check the input against teh set of rules
     * Returns an array of the messages inputted
     * @param string $how - Validation Rule
     * @param array $check - array(name => Return Message)
     * @param array $data - $_POST, $_GET, etc..
     * @return array 
     */
    static function check($how, $check, $data) {
        $r = array();
        foreach($check as $k => $v) {
            if(!Core_Helper_Validation::Validate($data[$k], $how)) {
                $r[] = $v;
            }
        }
        return $r;
    }
    
    /**
     * Validation Rules
     */
    static function Validate($Input, $Rule) {
        switch (strtolower($Rule)) {
            case 'number':
                $return = filter_var($Input, FILTER_VALIDATE_FLOAT);
                break;
            case 'int':
                $return = filter_var($Input, FILTER_VALIDATE_INT);
                break;
            case 'notnull':
                $return = (strlen(trim($Input)) > 0) ? true : false;
                break;
            case 'email':
                $return = filter_var($Input, FILTER_VALIDATE_EMAIL);
                break;
            case 'url':
                $return = filter_var($Input, FILTER_VALIDATE_URL);
                break;
            default:
                $return = false;
                break;
        }

        return $return;
    }

}