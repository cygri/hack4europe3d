<?php

class requestobject_Action extends Core_Framework_Action {

    function init() {
        $this->view->_layout = 'Json';
        
        //echo 'http://www.europeana.eu/portal/record/' . urldecode($_GET['uri']) . '';
        $search = file_get_contents('http://www.europeana.eu/portal/record/' . urldecode($_GET['uri']) . '');
        $return = json_decode($search);


        $this->view->return = $return;
    }

}

?>