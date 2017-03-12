<?php

namespace Cloudinary;

/**
 * Manages access to a cloudinary image as a field
 */
class CloudinaryField
{
    private $identifier = null;
    private $verifyUpload = false;

    public function __construct($identifier = '')
    {
        $this->identifier = $identifier;
    }

    public function __toString()
    {
        return (string)explode('#', $this->identifier())[0];
    }

    public function identifier()
    {
        return $this->identifier;
    }

    public function url($options = array())
    {
        if (!$this->identifier) {
            // TODO: Error?
            return;
        }
        return cloudinary_url($this, $options);
    }

    public function upload($file, $options = array())
    {
        $options['return_error'] = false;
        $ret = Uploader::upload($file, $options);
        $preloaded = new PreloadedFile(Cloudinary::signedPreloadedImage($ret));
        if ($this->verifyUpload && !$preloaded.is_valid()) {
            throw new \Exception("Error! Couldn't verify cloudinary response!");
        }
        $this->identifier = $preloaded->extendedIdentifier();
    }

    public function delete()
    {
        $options['return_error'] = false;
        $ret = Uploader::destroy($this->identifier);
        unset($this->identifier);
    }

    public function verify()
    {
        $preloaded = new PreloadedFile($this->identifier);
        return $preloaded->isValid();
    }
}
