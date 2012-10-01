<?php

/**
 * Simple uploading
 *
 * @author Mark
 */
class Core_Helper_Upload {

    /**
     * Upload a public image
     * -- Validates file type to have an image imei type
     * @param array $file
     * @return string|boolean 
     */
    public function image($file) {
        //Validate that it must be an image
        if (stripos($file["type"], 'image') === false) {
            return array('error' => 'Not an Image');
        } else {
            return Core_Helper_Upload::public_file($file);
        }
    }
    
    /**
     * Move $file from some dir to the public ul folder.
     * @param string $file
     */
    public static function move_to_public($file) {
        $ext = end(explode('.', $file));
        $path = self::generate_public_dir();
        if(!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $path .= time().md5($file).'.'.$ext;
        if (rename($file, $path)) {
            return $path;
        } else {
            return array('error' => 'Unable to upload file.');
        }
    }
    
    /**
     * Upload a file to a public upload path
     * @param array $file
     * @return string 
     */
    public static function public_file($file) {
        $ext = end(explode('.', $file['name']));
        $path = self::generate_public_dir();
        if(!is_dir($path)) {
            mkdir($path, 0755, true);
        }
        $path .= time().md5($file['name']).'.'.$ext;
        if (move_uploaded_file($file['tmp_name'], $path)) {
            return $path;
        } else {
            return array('error' => 'Unable to upload file.');
        }
    }
    
    public static function generate_public_dir() {
        return PUBLIC_DIR.'ul/'.strtotime(date('Y-m')).'/';
    }

}

?>
