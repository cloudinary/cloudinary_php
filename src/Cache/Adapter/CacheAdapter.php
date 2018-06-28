<?php namespace Cloudinary\Cache\Adapter;

/**
 * Interface CacheAdapter
 * @package Cloudinary\Cache\Adapter
 */
interface CacheAdapter
{
    /**
     * Gets value specified by parameters
     *
     * @param $publicId
     * @param $type
     * @param $resourceType
     * @param $transformation
     * @param $format
     *
     * @return null|mixed
     */
    public function get($publicId, $type, $resourceType, $transformation, $format);

    /**
     * Sets value specified by parameters
     *
     * @param $publicId
     * @param $type
     * @param $resourceType
     * @param $transformation
     * @param $format
     * @param $value
     *
     * @return mixed
     */
    public function set($publicId, $type, $resourceType, $transformation, $format, $value);

    /**
     * Deletes entry specified by parameters
     *
     * @param $publicId
     * @param $type
     * @param $resourceType
     * @param $transformation
     * @param $format
     *
     * @return mixed
     */

    public function delete($publicId, $type, $resourceType, $transformation, $format);

    /**
     * Flushes all entries from cache
     *
     * @return bool
     */
    public function flushAll();
}
