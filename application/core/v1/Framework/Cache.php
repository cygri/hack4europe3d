<?php

class Core_Framework_Cache {

    public static function cached($file, $vars = false) {
        $page = md5($_SERVER['REQUEST_URI']);
        $dir = ACCOUNT_DIR . 'cache/' . $page;
        $cachefile = $dir . '/' . md5($file) . '.html';

        //Cache the file if it dosen't exist
        if (!file_exists($cachefile) || Core_Framework_Singleton::config()->ini['web_shop']['disablecache'] == 'yes') {
            //Make sure the folder exists
            if (!is_dir($dir)) {
                mkdir($dir, 0755);
            }

            //HTML Cache
            ob_start();
            Core_Framework_View::getPartial($file, $vars);
            $output = ob_get_contents();
            ob_end_clean();


            $handle = fopen($cachefile, 'w');
            fwrite($handle, $output);
            fclose($handle);
        }

        include($cachefile);
    }

    public static function clearCache() {
        $dir = ACCOUNT_DIR . 'cache';
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file)) {
                self::rrmdir($file);
            } else {
                unlink($file);
            }
        }
    }

    public static function rrmdir($dir) {
        foreach (glob($dir . '/*') as $file) {
            if (is_dir($file)) {
                self::rrmdir($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dir);
    }

    public static function cachedReturn($file, $folder = false, $vars = false) {
        if (!$folder) {
            $page = md5($_SERVER['REQUEST_URI']);
        } else {
            $page = $folder;
        }
        $dir = ACCOUNT_DIR . 'cache/' . $page;
        $cachefile = $dir . '/' . md5($file) . '.php';

        $path = "view/_partials/" . $file . '.php';

        //Cache the file if it dosen't exist
        if (!file_exists($cachefile) || Core_Framework_Singleton::config()->ini['web_shop']['disablecache'] == 'yes') {
            //if (true) {
            //Make sure the folder exists
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }

            if (file_exists(ACCOUNT_DIR . $file)) {
                $store = include ACCOUNT_DIR . $path;
            } else {
                $store = include CORE_DIR . $path;
            }

            $output = serialize($store);
            $handle = fopen($cachefile, 'w');
            fwrite($handle, $output);
            fclose($handle);
        } else {
            $store = file_get_contents($cachefile);
            $store = unserialize($store);
        }

        return $store;
    }

}