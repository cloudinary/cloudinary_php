<?php
/**
 * Manages access to a cloudinary image as a field
 */

require_once '../../../src/Cloudinary.php';
require_once '../../../src/Uploader.php';

class CloudinaryField extends Object {
    private $identifier = NULL;
    private $autoSave = false;
    private $verifyUpload = false;

    public function __construct($identifier = "") {
        error_log("CloudinaryField::__construct - " . $identifier);
        $this->identifier = $identifier;
    }

    public function __toString() {
        return explode("#", $this->identifier)[0];
    }

    public function url($options = array()) {
        if (!$this->identifier) {
            // TODO: Error?
            return;
        }
        return cloudinary_url($this->identifier, $options);
    }

    public function upload($file, $options = array()) {
        $options['return_error'] = false;
        $ret = \Cloudinary\Uploader::upload($file, $options);
        $preloaded = new \Cloudinary\PreloadedFile(\Cloudinary::signed_preloaded_image($ret));
        if ($this->verifyUpload && !$preloaded.is_valid()) {
            throw new \Exception("Error! Couldn't verify cloudinary response!");
        }
        $this->identifier = $preloaded->extended_identifier();
    }

    public function delete() {
        $options['return_error'] = false;
        $ret = \Cloudinary\Uploader::destroy($this->identifier);
        unset($this->identifier);
    }

    public function verify() {
        $preloaded = new \Cloudinary\PreloadedFile($this->identifier);
        return $preloaded->is_valid();
    }
}
