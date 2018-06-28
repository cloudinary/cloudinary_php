<?php namespace Cloudinary\Cache\Storage;

/**
 * Interface KeyValueStorageInterface
 * @package Cloudinary\Cache\Storage
 */
interface KeyValueStorage
{
    /**
     * Gets value by key
     *
     * @param string $key
     *
     * @return string|null value
     */
    public function get($key);

    /**
     * Sets value by key
     *
     * @param string $key
     * @param string $value
     *
     * @return bool which indicates whether operation succeeded
     */
    public function set($key, $value);

    /**
     * Deletes item by key
     *
     * @param string $key
     *
     * @return bool which indicates whether operation succeeded
     */
    public function delete($key);

    /**
     * Clears all entries
     *
     * @return bool which indicates whether operation succeeded
     */
    public function clear();
}
