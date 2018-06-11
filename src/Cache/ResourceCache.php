<?php

namespace Cloudinary\Cache;

use Cloudinary\Utils\Singleton;

/**
 * Class ResourceCache
 * @package Cloudinary
 */
class ResourceCache extends Singleton
{

    /**
     * @var object $cache  Cache instance
     */
    protected $cache;

    /**
     * @param array $cacheOptions
     */
    public function init($cacheOptions = array())
    {
        $cache = \Cloudinary::option_get($cacheOptions, "cache");
        if (is_null($cache)) {
            $cache = $this->getConnectorFromConfig($cacheOptions);
        }

        $this->setCacheConnector($cache);
    }

    /**
     * @param array $cacheOptions
     *
     * @return null
     */
    protected function getConnectorFromConfig($cacheOptions = array())
    {
        return null;
    }

    /**
     * Indicates whether cache is enabled or not
     *
     * @return bool
     */
    public function enabled()
    {
        return !is_null($this->cache);
    }

    /**
     * @param $cache
     *
     * @return bool
     */
    public function setCacheConnector($cache)
    {
        if (is_null($cache)) {
            return false;
        }

        # TODO: check if is instance of Cache interface or supports all methods, etc
        $this->cache = $cache;

        return true;
    }

    /**
     * @param string $publicId
     * @param array  $options
     *
     * @return string
     */
    public static function generateCacheKey($publicId, $options)
    {
        $type = \Cloudinary::option_get($options, "type", "upload");
        $resourceType = \Cloudinary::option_get($options, "resource_type", "image");

        return sha1(implode("/", array_filter(array($resourceType, $type, $publicId))));
    }

    /**
     * @param                       $publicId
     * @param array                 $options
     * @param array|CacheResource   $value
     *
     * @return null
     */
    public function set($publicId, $options, $value)
    {
        if (!$this->enabled()) {
            return false;
        }

        return $this->cache->set(self::generateCacheKey($publicId, $options), json_encode($value));
    }

    /**
     * @param       $publicId
     * @param array $options
     * @param       $value
     *
     * @return null
     */
    public function update($publicId, $options, $value)
    {
        if (!$this->enabled()) {
            return false;
        }

        // Update with null value is no op
        if (is_null($value)) {
            return false;
        }

        // TODO: LOCK this code, make it atomic, there is a race here!
        $resourceToUpdate = $this->get($publicId, $options);

        if (is_null($resourceToUpdate)) {
            $resourceToUpdate = new CacheResource();
        }

        $resourceToUpdate->updateWithOther($value);

        $result = $this->set($publicId, $options, $resourceToUpdate);
        // TODO: END OF LOCK
        return $result;
    }

    /**
     * @param $publicId
     * @param $options
     *
     * @return CacheResource
     */
    public function get($publicId, $options)
    {
        if (!$this->enabled()) {
            return null;
        }

        $result = json_decode($this->cache->get(self::generateCacheKey($publicId, $options)), true);

        return is_null($result) ? null: new CacheResource($result);
    }

    /**
     * @param $publicId
     * @param $options
     *
     * @return null
     */
    public function delete($publicId, $options)
    {
        if (!$this->enabled()) {
            return null;
        }

        return $this->cache->delete(self::generateCacheKey($publicId, $options));
    }

    /**
     *
     */
    public function clear()
    {
        if (!$this->enabled()) {
            return null;
        }

        return $this->cache->clear();
    }
}
