<?php
class Core_Framework_Auth {
   
    /**
     * Session data
     * @var Zend_Session_Namespace 
     */
   public $session;
   
   /* @var $cart Core_Intact_Cart */
   
   function __construct() {
       Zend_Session::start();
       $this->session = new Zend_Session_Namespace(SITE_KEY);
   }
}