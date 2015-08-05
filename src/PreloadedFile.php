<?php
namespace Cloudinary;

class PreloadedFile
{
    public static $PRELOADED_CLOUDINARY_PATH = '/^([^\/]+)\/([^\/]+)\/v(\d+)\/([^#]+)#([^\/]+)$/';

    public $filename;
    public $version;
    public $public_id;
    public $signature;
    public $resource_type;
    public $type;

    public function __construct($file_info)
    {
        if (!preg_match(self::$PRELOADED_CLOUDINARY_PATH, $file_info, $matches)) {
            throw new \InvalidArgumentException('Invalid preloaded file info');
        }
        $this->resource_type = $matches[1];
        $this->type = $matches[2];
        $this->version = $matches[3];
        $this->filename = $matches[4];
        $this->signature = $matches[5];
        $public_id_and_format = $this->splitFormat($this->filename);
        $this->public_id = $public_id_and_format[0];
        $this->format = $public_id_and_format[1];
    }

    public function isValid()
    {
        $public_id = $this->resource_type == 'raw' ? $this->filename : $this->public_id;
        $expected_signature = Cloudinary::apiSignRequest(
            array('public_id' => $public_id, 'version' => $this->version),
            Cloudinary::configGet('api_secret')
        );
        return $this->signature == $expected_signature;
    }

    protected function splitFormat($identifier)
    {
        $last_dot = strrpos($identifier, '.');

        if ($last_dot === false) {
            return array($identifier, null);
        }
        $public_id = substr($identifier, 0, $last_dot);
        $format = substr($identifier, $last_dot + 1);
        return array($public_id, $format);
    }

    public function identifier()
    {
        return "v{$this->version}/{$this->filename}";
    }

    public function extendedIdentifier()
    {
        return "{$this->resource_type}/{$this->type}/{$this->identifier()}";
    }

    public function __toString()
    {
        return "{$this->resource_type}/{$this->type}/v{$this->version}/{$this->filename}#{$this->signature}";
    }
}
