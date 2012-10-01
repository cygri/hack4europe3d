<?php

/**
 * View class
 *
 * @author Mark
 */
class Core_Framework_View {

    public $_layout = 'Default';
    public $js = array();

    public function __construct() {
        $this->_layout = Core_Framework_Singleton::config()->ini['web_shop']['layout'];
        $this->session = Core_Framework_Singleton::auth()->session;
    }

    public function getPartial($file, $vars = false) {
        $path = "view/_partials/" . $file . ".php";

        //Check for a custom version
        if (file_exists(ACCOUNT_DIR . $path)) {
            include(ACCOUNT_DIR . $path);
        } else {
            include(CORE_DIR . $path);
        }
    }

    public function cached($file, $vars = false) {
        Core_Framework_Cache::cached($file, $vars);
    }

    public function cachedReturn($file, $folder = false, $vars = false) {
        $store = Core_Framework_Cache::cachedReturn($file, $folder, $vars);
        return $store;
    }

    public function clearCache() {
        Core_Framework_Cache::clearCache();
    }

    public function rrmdir($dir) {
        Core_Framework_Cache::rrmdir($dir);
    }

    public function renderView($dir) {
        $header = 'layouts/' . $this->_layout . '/header.php';
        $footer = 'layouts/' . $this->_layout . '/footer.php';
        $view = "view/" . $dir . '/view.php';

        //Check to see if this is a custom header
        if (file_exists(ACCOUNT_DIR . $header)) {
            include(ACCOUNT_DIR . $header);
        } else {
            include(CORE_DIR . $header);
        }

        //Check to see if this is a custom view
        if (file_exists(ACCOUNT_DIR . $view)) {
            include(ACCOUNT_DIR . $view);
        } else {
            include(CORE_DIR . $view);
        }

        //Check to see if this is a custom footer
        if (file_exists(ACCOUNT_DIR . $footer)) {
            include(ACCOUNT_DIR . $footer);
        } else {
            include(CORE_DIR . $footer);
        }
    }

    /**
     * Secure PHP Thumb resizing
     */
    public function thumb($img, $properties) {
        $file = $img;
        $ext = end(explode('.', $img));
        $kx = '';
        foreach ($properties as $k => $v) {
            $kx .= $k . $v;
        }
        $dir = PUBLIC_DIR . "images/thumbs/";
        $thumb = $dir . hash_hmac('ripemd160', $img . $kx, 'sThumbz1') . '.' . $ext;

        if (!$properties['q']) {
            $properties['q'] = 100;
        }

        if (file_exists($file) && !file_exists($thumb)) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            $phpThumb = new phpthumb();

            $PHPTHUMB_CONFIG = array(
                'document_root' => ROOT_DIR,
                'cache_directory' => ROOT_DIR . '../application/plugins/phpThumb/cache/',
                'imagemagick_path' => 'C:/Program Files (x86)/ImageMagick-6.7.7-Q16/convert.exe',
                'disable_debug' => false
            );
            foreach ($PHPTHUMB_CONFIG as $key => $value) {
                $keyname = 'config_' . $key;
                $phpThumb->setParameter($keyname, $value);
            }


            $phpThumb->setSourceFilename(ROOT_DIR . $file);
            foreach ($properties as $k => $v) {
                $phpThumb->setParameter($k, $v);
            }
            if ($gt = $phpThumb->GenerateThumbnail()) {
                if ($rt = $phpThumb->RenderToFile(ROOT_DIR . $thumb)) {
                    return $thumb;
                } else {
                    return $img;
                }
            } else {
                return $img;
            }
        } elseif (file_exists($thumb)) {
            /*
             * Thumb was already made!
             */
            return $thumb;
        } else {
            /*
             * Return the original input as no file was found in the ul folder
             */
            return $img;
        }
    }

    /**
     * Try find a CMS page because the action wasn't found.
     * @param Core_Framework_Action $action
     * @return boolean
     */
    public static function get_cms_page($action) {
        $pages = (Core_Framework_Cache::cachedReturn('system/tree_array', 'system'));
        if ($page = $pages[$action->page]) {
            $page_cms = new Core_Table_CMS($page);
            if($page_cms->Type == 'Link') {
                Core_Helper_Utils::redirect($page_cms->Text);
            }
            $cms_action = new Core_Framework_CMSAction($page);
            $cms_action->CMS = $page_cms;
            $cms_action->page_dir = $page_cms->Template;
            return $cms_action;
        }
        return false;
    }

    /**
     * Returns the html of a breadcrumb link.
     * @param string $link
     * @param string $text
     * @param boolean $active
     * @return string
     */
    public static function breadcrumb_item($link, $text, $active = false) {
        if (!$active) {
            $r = '<li> <a href="' . $link . '">' . $text . '</a> <span class="divider">/</span> </li>';
        } else {
            $r = '<li class="active">' . $text . '</li>';
        }

        return $r;
    }

    
    public static function set_flood() {
        Core_Framework_Singleton::view()->flood_code = Core_Intact_Action::flood_code();
    }
}

?>