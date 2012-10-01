<?php

class save_Action extends Core_Framework_Action {

    function init() {
        $this->view->_layout = 'Json';
        $item = $_POST;

        if ($item['dc:title']) {
            $plain_title = $item['dc:title'];
        } else {
            $plain_title = '';
        }

        $description = '';
        if ($item['dcterms:alternative']) {
            $description = $item['dcterms:alternative'][1];
        }
        
        $_POST['plain_title'] = $plain_title;
        $_POST['description'] = $description;

        $this->session->collection['a'.md5($item['europeana:object'])] = $_POST;
        $return = array('success' => 'true');
        $this->view->return = $return;
    }

}

?>