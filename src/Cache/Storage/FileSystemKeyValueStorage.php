<?php

namespace Cloudinary\Cache\Storage;

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
     * @param $rootPath
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
     * Gets value by key
     *
     * @param string $key
     *
     * @return string|null value
     */
    public function get($key)
    {
        if (!$this->exists($key)) {
            return null;
        }

        return file_get_contents($this->getKeyFullPath($key));
    }

    /**
     * Sets value by key
     *
     * @param string $key
     * @param string $value
     *
     * @return bool which indicates whether operation succeeded
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
     * Deletes item by key
     *
     * @param string $key
     *
     * @return bool which indicates whether operation succeeded
     */
    public function delete($key)
    {
        if (!$this->exists($key)) {
            return true;
        }

        return unlink($this->getKeyFullPath($key));
    }

    /**
     * Clears all entries
     *
     * @return bool which indicates whether operation succeeded
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
     * @return string
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
