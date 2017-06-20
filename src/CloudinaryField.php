<?php
/**
 * Manages access to a cloudinary image as a field
 */

require_once 'Cloudinary.php';
require_once 'Uploader.php';
require_once 'PreloadedFile.php';

class CloudinaryField {
    private $_identifier = NULL;

    public function __construct($identifier = "") {
        $this->_identifier = $identifier;
    }

    public function __toString() {
        return (string) explode("#", $this->identifier())[0];
    }

    public function identifier() {
        return $this->_identifier;
    }

    public function url($options = array()) {
        if (!$this->_identifier) {
            // TODO: Error?
            return null;
        }
        return cloudinary_url($this, $options);
    }

    public function upload($file, $options = array()) {
        $options['return_error'] = false;
        $ret = \Cloudinary\Uploader::upload($file, $options);
        $preloaded = new \Cloudinary\PreloadedFile(\Cloudinary::signed_preloaded_image($ret));
        $this->_identifier = $preloaded->extended_identifier();
    }

    public function delete() {
        $options['return_error'] = false;
        $ret = \Cloudinary\Uploader::destroy($this->_identifier);
        unset($this->_identifier);
    }

    public function verify() {
        $preloaded = new \Cloudinary\PreloadedFile($this->_identifier);
        return $preloaded->is_valid();
    }
}
