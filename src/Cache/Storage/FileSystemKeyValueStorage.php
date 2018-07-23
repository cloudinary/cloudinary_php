<?php namespace Cloudinary\Cache\Storage;

use GlobIterator;

/**
 * Class FileKeyValueStorage
 * @package Cloudinary\Cache\Storage
 */
class FileSystemKeyValueStorage implements KeyValueStorage
{
    private $rootPath;

    /**
     * @var string Cache item extension
     */
    private static $itemExt = ".cldci";

    /**
     * FileKeyValueStorage constructor.
     *
     * @param string $rootPath The root path of the cache
     */
    public function __construct($rootPath)
    {
        if (is_null($rootPath)) {
            $rootPath = sys_get_temp_dir();
        }

        if (!is_dir($rootPath)) {
            $result = mkdir($rootPath);
            if ($result === true) {
                chmod($rootPath, 0777);
            }
        }

        $this->rootPath = $rootPath;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (!$this->exists($key)) {
            return null;
        }

        return file_get_contents($this->getKeyFullPath($key));
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $bytesWritten = file_put_contents($this->getKeyFullPath($key), $value);

        if ($bytesWritten === false) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        if (!$this->exists($key)) {
            return true;
        }

        return unlink($this->getKeyFullPath($key));
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $success = true;

        $cacheItems = new GlobIterator($this->rootPath . DIRECTORY_SEPARATOR . "*" . self::$itemExt);

        if (!$cacheItems->count()) {
            return true;
        }

        foreach ($cacheItems as $itemPath) {
            if (!unlink($itemPath)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Gets the file path for a key.
     *
     * @param string $key
     *
     * @return string Absolute file path
     */
    private function getKeyFullPath($key)
    {
        return $this->rootPath . DIRECTORY_SEPARATOR . $key . self::$itemExt;
    }

    /**
     * Indicates whether key exists
     *
     * @param $key
     *
     * @return bool
     */
    private function exists($key)
    {
        return file_exists($this->getKeyFullPath($key));
    }
}
