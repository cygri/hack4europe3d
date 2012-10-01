<?php

class remove_Action extends Core_Framework_Action {

    function init() {
        $this->view->_layout = 'Json';
        

        $this->session->collection[$_POST['code']] = false;
        unset($this->session->collection[$_POST['code']]);
        $return = array('success' => 'true');
        $this->view->return = $return;
    }

}

?>