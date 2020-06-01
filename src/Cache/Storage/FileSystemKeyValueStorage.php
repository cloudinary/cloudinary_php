<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Cache\Storage;

use GlobIterator;

/**
 * File-based key-value storage.
 *
 * @api
 */
class FileSystemKeyValueStorage implements KeyValueStorage
{
    /**
     * The root path of the storage.
     *
     * @var string $rootPath
     */
    private $rootPath;

    /**
     * @var string Cache files extension.
     */
    private static $itemExt = '.cldci';

    /**
     * Creates a new Storage object.
     *
     * All files will be stored under the $rootPath location.
     *
     * @param string $rootPath The base folder for all storage files.
     */
    public function __construct($rootPath)
    {
        if ($rootPath === null) {
            $rootPath = sys_get_temp_dir();
        }

        if (! is_dir($rootPath)) {
            $result = mkdir($rootPath);
            if ($result === true) {
                chmod($rootPath, 0777);
            }
        }

        $this->rootPath = $rootPath;
    }


    /**
     * Gets a value identified by the given $key.
     *
     * @param string $key A unique identifier.
     *
     * @return mixed|null The value identified by $key or null if no value was found.
     */
    public function get($key)
    {
        if (! $this->exists($key)) {
            return null;
        }

        return file_get_contents($this->getKeyFullPath($key));
    }


    /**
     * Stores the $value identified by the $key.
     *
     * @param string $key A unique identifier.
     * @param mixed  $value
     *
     * @return bool true on success or false on failure.
     */
    public function set($key, $value)
    {
        $bytesWritten = file_put_contents($this->getKeyFullPath($key), $value);

        return ! ($bytesWritten === false);
    }


    /**
     * Deletes item by key.
     *
     * @param string $key A unique identifier.
     *
     * @return bool true on success or false on failure.
     */
    public function delete($key)
    {
        if (! $this->exists($key)) {
            return true;
        }

        return unlink($this->getKeyFullPath($key));
    }

    /**
     * Clears all entries.
     *
     * @return bool Returns true if the operation was successful.
     */
    public function clear()
    {
        $success = true;

        $cacheItems = new GlobIterator($this->rootPath . DIRECTORY_SEPARATOR . '*' . self::$itemExt);

        if (! $cacheItems->count()) {
            return true;
        }

        foreach ($cacheItems as $itemPath) {
            if (! unlink($itemPath)) {
                $success = false;
            }
        }

        return $success;
    }

    /**
     * Generates the file path for the $key.
     *
     * @param string $key The key.
     *
     * @return string The absolute path of the value file associated with the $key.
     */
    private function getKeyFullPath($key)
    {
        return $this->rootPath . DIRECTORY_SEPARATOR . $key . self::$itemExt;
    }

    /**
     * Indicates whether key exists.
     *
     * @param string $key The key.
     *
     * @return bool True if the file for the given $key exists.
     */
    private function exists($key)
    {
        return file_exists($this->getKeyFullPath($key));
    }
}
