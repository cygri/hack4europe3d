<?php

    class search_Action extends Core_Framework_Action {
        function init() {
                $this->view->_layout = 'Json';  
                $search = file_get_contents('http://api.europeana.eu/api/opensearch.json?startPage='.$_GET['startpage'].'&searchTerms='.urlencode($_GET['term']).'&wskey=ROAMXHDBDQ');
                $return = json_decode($search);
                
                
                $this->view->return = $return;
        }
    }

?>