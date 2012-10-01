<?php

error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

//Start Settings
date_default_timezone_set("Europe/Dublin");

$application_dir = realpath($_SERVER['DOCUMENT_ROOT'] . '/../application/') . '/';

define('APPLICATION_DIR', $application_dir);
$config_file = $application_dir . 'configs/config.ini';
$domain_name = $_SERVER['HTTP_HOST'];

//Include full config
$ini_array = parse_ini_file($config_file, true);

//Get Site key based on domain name
if ($site_key = $ini_array['sites'][$domain_name]) {

    //Check the config file exists
    $site_config = $application_dir . 'configs/' . $site_key . '.ini';
    if (file_exists($site_config)) {

        //Get Site Settings and include cross_site settings
        $cross_site = $ini_array['cross_site'];
        $ini_array = parse_ini_file($site_config, true);

        //Merge Cross Site Vars
        if ($cross_site && $ini_array['cross_site']) {
            $ini_array['cross_site'] = ($ini_array['cross_site'] + $cross_site);
        } elseif(!$cross_site && $ini_array['cross_site']) {
            $ini_array['cross_site'] = $cross_site;
        }

        //Define system vars
        define('CORE_VERSION', $ini_array['web_shop']['core']);
        define('SITE_VERSION', $ini_array['web_shop']['version']);
        define('SITE_KEY', $site_key);

        define('CORE_DIR', APPLICATION_DIR . 'core/' . CORE_VERSION . "/");
        define('ACCOUNT_DIR', APPLICATION_DIR . 'sites/' . SITE_KEY . '/' . SITE_VERSION . "/");

        define('PUBLIC_DIR', 'sites/' . SITE_KEY . '/' . SITE_VERSION . "/");
        define('ROOT_DIR', $_SERVER['DOCUMENT_ROOT'] . '/');

        //Autload classes
        function custom_loader($className) {
                    $dir = explode('_', $className);
                    $site_key = $dir[0];
                    $file = end($dir);

                    unset($dir[0], $dir[(count($dir))]);

                    if ($site_key == 'Core') {
                        $dir = CORE_DIR . implode('/', $dir) . '/' . $file . '.php';
                    } else {
                        $dir = ACCOUNT_DIR . implode('/', $dir) . '/' . $file . '.php';
                    }
                    if (file_exists($dir)) {
                        include($dir);
                    }
                }
        spl_autoload_register('custom_loader');

        set_include_path($application_dir . "plugins/" . PATH_SEPARATOR . get_include_path());
        //set_include_path(get_include_path() . PATH_SEPARATOR . $application_dir . "plugins/PHPExcel/Classes/");

        require_once 'Zend/Loader/Autoloader.php';
        // instantiate the loader
        $loader = Zend_Loader_Autoloader::getInstance();
        // optional argument if you want the auto-loader to load ALL namespaces
        $loader->setFallbackAutoloader(false);

        //PHP Thumb of
        require_once 'phpThumb/phpThumb.config.php';
        require_once 'phpThumb/phpthumb.functions.php';
        require_once 'phpThumb/phpthumb.class.php';

        //Get the core framework and start
        $framework = new Core_Framework_Init();
        $framework->start($ini_array);
    } else {
        echo 'Config File not found.';
        exit();
    }
} else {
    echo 'No config key found.';
    exit();
}