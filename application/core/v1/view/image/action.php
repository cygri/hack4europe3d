<?php

/**
 * GD Padding Lib Plugin Definition File
 * 
 * This file contains the plugin definition for the GD Padding Lib for PHP Thumb
 * 
 * PHP Version 5 with GD 2.0+
 * PhpThumb : PHP Thumb Library <http://phpthumb.gxdlabs.com>
 */

/**
 * GD Padding Lib Plugin
 * 
 * Example usage:
 * 
 * require_once '../ThumbLib.inc.php';
 * $thumb = PhpThumbFactory::create('test.jpg');
 * $thumb->resize(100,100)->padding(150,120,'#efefef');
 * $thumb->show();
 * 
 * This plugin allows you to put an image into a bigger box and align it vertically and horizontally centered, so there is a padding on the sides if the source images dimensions are smaller than the box.
 * 
 * @package PhpThumb
 * @subpackage Plugins
 */
class PaddingLib {

    /**
     * Instance of GdThumb passed to this class
     * 
     * @var GdThumb
     */
    protected $parentInstance;
    protected $currentDimensions;
    protected $workingImage;
    protected $newImage;
    protected $options;

    /**
     * Pad the image
     * 
     * @param int $newWidth
     * @param int $newHeieight
     * @param int $backgroundColor
     * @return GdThumb
     */
    public function padding($newWidth, $newHeight, $backgroundColor, &$that) {
        // bring stuff from the parent class into this class...
        $this->parentInstance = $that;
        $this->currentDimensions = $this->parentInstance->getCurrentDimensions();
        $this->workingImage = $this->parentInstance->getWorkingImage();
        $this->newImage = $this->parentInstance->getOldImage();
        $this->options = $this->parentInstance->getOptions();

        $width = $this->currentDimensions['width'];
        $height = $this->currentDimensions['height'];

        $offsetLeft = ($newWidth - $width) / 2;
        $offsetTop = ($newHeight - $height) / 2;

        $this->workingImage = imagecreatetruecolor($newWidth, $newHeight);

        $rgb = $this->hex2rgb($backgroundColor, false);

        $colorToPaint = imagecolorallocatealpha($this->workingImage, $rgb[0], $rgb[1], $rgb[2], 0);
        imagefilledrectangle($this->workingImage, 0, 0, $newWidth, $newHeight, $colorToPaint);

        imagecopy($this->workingImage, $this->newImage, $offsetLeft, $offsetTop, 0, 0, $width, $height);

        $this->parentInstance->setOldImage($this->workingImage);

        return $that;
    }

    /**
     * Converts a hex color to rgb tuples
     * 
     * @return mixed 
     * @param string $hex
     * @param bool $asString
     */
    protected function hex2rgb($hex, $asString = false) {
        // strip off any leading #
        if (0 === strpos($hex, '#')) {
            $hex = substr($hex, 1);
        } elseif (0 === strpos($hex, '&H')) {
            $hex = substr($hex, 2);
        }

        // break into hex 3-tuple
        $cutpoint = ceil(strlen($hex) / 2) - 1;
        $rgb = explode(':', wordwrap($hex, $cutpoint, ':', $cutpoint), 3);

        // convert each tuple to decimal
        $rgb[0] = (isset($rgb[0]) ? hexdec($rgb[0]) : 0);
        $rgb[1] = (isset($rgb[1]) ? hexdec($rgb[1]) : 0);
        $rgb[2] = (isset($rgb[2]) ? hexdec($rgb[2]) : 0);

        return ($asString ? "{$rgb[0]} {$rgb[1]} {$rgb[2]}" : $rgb);
    }

}

class image_Action extends Core_Framework_Action {

    function init() {
        //generate image

        $width = 256;
        $height = 256;

        /* Instanciate and read the image in */
        $im = new Imagick(base64_decode($_GET['uri']));

        /* Fit the image into $width x $height box
          The third parameter fits the image into a "bounding box" */
        $im->thumbnailImage($width, $height, true);

        /* Create a canvas with the desired color */
        $canvas = new Imagick();
        $canvas->newImage($width, $height, '#f2e6d8', 'png');

        /* Get the image geometry */
        $geometry = $im->getImageGeometry();
        $im->rotateimage('#f2e6d8', 180);
        /* The overlay x and y coordinates */
        $x = ( $width - $geometry['width'] ) / 2;
        $y = ( $height - $geometry['height'] ) / 2;

        /* Composite on the canvas  */
        $canvas->compositeImage($im, imagick::COMPOSITE_OVER, $x, $y);

        /* Output the image */
        header("Content-Type: image/png");
        echo $canvas;

        exit();
    }

}
