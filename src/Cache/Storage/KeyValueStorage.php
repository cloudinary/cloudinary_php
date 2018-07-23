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
     * @return string|null value, null if not found
     */
    public function get($key);

    /**
     * Sets value by key
     *
     * @param string $key
     * @param string $value
     *
     * @return bool true on success or false on failure
     */
    public function set($key, $value);

    /**
     * Deletes item by key
     *
     * @param string $key
     *
     * @return bool true on success or false on failure
     */
    public function delete($key);

    /**
     * Clears all entries
     *
     * @return bool true on success or false on failure
     */
    public function clear();
}
