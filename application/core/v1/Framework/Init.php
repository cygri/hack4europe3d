<?php

/**
 * Startup bootstrapper file
 *
 * @author Mark
 */
class Core_Framework_Init {

    public $page, $folders, $page_dir;
    private $config = false;

    /**
     * Starts the Intact Web Shop
     * @param array $ini - Passes through all Intact Config settings
     */
    function start($ini) {
        //Config data
        $config = Core_Framework_Singleton::config();
        $config->attach($ini);
        $this->config = $config;
        
        $this->clean_globals();
        $this->set_page();
        $this->get_action();
        $this->set_view();
    }

    /**
     * Sets and loads the 
     */
    function set_page() {
        //Page URL information
        $this->page = $_SERVER['REQUEST_URI'];
        
        if ($this->page == '/') {
            //The index page by default
            $this->page = '/index.html';
        }
        
        //Get the folder information
        $this->folders = explode("/", $this->page);
        unset($this->folders[0]);
        
        //Fix last folder extension
        $last_folder = explode('.html', end($this->folders));
        unset($last_folder[count($last_folder)-1]);
        $last_folder = implode('.', $last_folder);
        $this->folders[count($this->folders)] = $last_folder;
        $this->page_dir = str_replace(array('..', '//'), '', implode('/', $this->folders));
        define('PAGE_DIR', $this->page_dir);
        
    }
    
    /**
     * Gets and load the page related action file 
     */
    function get_action() {
        $action_name = strtolower(end($this->folders));
        
        //Make sure this action exists
        $core_action = CORE_DIR."view/".$this->page_dir.'/action.php';
        if(file_exists($core_action)) {
            include $core_action;
            $action = $action_name."_Action";
        } else {
            if(!$action = Core_Framework_View::get_cms_page($this)) {
                Core_Framework_Helper::error404();
            } else {
                $this->page_dir = '../layouts_cms/'.$action->page_dir;
            }
        }
        
        //See if there is a custom action file
        $account_action = ACCOUNT_DIR."view/".$this->page_dir.'/action.php';
        if(file_exists($account_action)) {
            include $account_action;
            $action = $action_name."_Action";
        }
        
        if(is_string($action)) { $action = new $action(); }
        $action->pre();
        $action->init();
        $action->post();
    }
    
    /**
     * Gets the current view including layout template
     */
    function set_view() {
        Core_Framework_Singleton::view()->renderView($this->page_dir);
    }

    
    public function clean_globals() {
        $_POST = Core_Helper_Sanitize::html($_POST);
        $_REQUEST = Core_Helper_Sanitize::html($_REQUEST);
        $_GET = Core_Helper_Sanitize::html($_GET);
    }
}

?>