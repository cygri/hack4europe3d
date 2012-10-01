<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TwitterBootstrap
 *
 * @author Mark
 */
class Core_Helper_TwitterBootstrap {

    static function input($label, $name, $value = "", $type='text') {
        return '<div class="control-group">
                    <label for="frm-' . $name . '" class="control-label">'.$label.'</label>
                    <div class="controls">
                    <input id="frm-' . $name . '" type="' . $type . '" name="' . $name . '" value="' . (($_REQUEST[$name]) ? $_REQUEST[$name] : $value) . '" class="input-xlarge" />
                    </div>
                </div>';
    }
    
    static function submit($value, $name = '', $label = "", $class = "") {
        return '<div class="control-group">
                    <label for="input01" class="control-label">'.$label.'</label>
                    <div class="controls">
                        <input type="submit" '.(($name) ? 'name="'.$name.'"' : '').' class="btn input-xlarge '.$class.'" value="'.$value.'">
                    </div>
                </div>';
    }
    
    

}

?>
