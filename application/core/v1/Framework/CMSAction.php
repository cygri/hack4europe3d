<?php
class Core_Framework_CMSAction extends Core_Framework_Action {
    /**
     * The CMS page object
     * @var Core_Framework_CMSAction 
     */
    public $CMS = false;
    
    /**
     * Directory to content
     * @var string 
     */
    public $page_dir = false;
     
    public function __construct() {
        parent::__construct();
        
    }
    public function init() {
        parent::init();
    }
    
    public function post() {
        parent::post();
        
        $this->view->CMS = $this->CMS;
    }
}