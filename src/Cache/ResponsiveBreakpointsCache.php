<?php

namespace Cloudinary\Cache;

use Cloudinary\Utils\Singleton;

/**
 * Class ResponsiveBreakpointsCache
 * @package Cloudinary
 */
class ResponsiveBreakpointsCache extends Singleton
{
    /**
     * @var object $resourceCache  Cache instance
     */
    protected $resourceCache;

    /**
     * ResponsiveBreakpointsCache constructor.
     *
     */
    protected function __construct()
    {
        $this->resourceCache = ResourceCache::instance();
    }

    /**
     * @param array $cacheOptions
     */
    public function init($cacheOptions = [])
    {
        $this->resourceCache->init($cacheOptions);
    }

    /**
     * Indicates whether cache is enabled or not
     *
     * @return bool
     */
    public function enabled()
    {
        return $this->resourceCache->enabled();
    }

    /**
     * @param $cache
     *
     * @return bool
     */
    public function setCacheConnector($cache)
    {
        return $this->resourceCache->setCacheConnector($cache);
    }

    /**
     * @param string $publicId
     * @param array  $options
     *
     * @return string
     */
    public function generateCacheKey($publicId, $options)
    {
        return $this->resourceCache->generateCacheKey($publicId, $options);
    }

    /**
     * @param       $publicId
     * @param array $options
     * @param       $value
     *
     * @return null
     */
    public function set($publicId, $options, $value)
    {
        if (!$this->enabled()) {
            return false;
        }

        $cacheResource = new CacheResource();
        $cacheResource->setBreakpoints($options, $value);

        return $this->resourceCache->set($publicId, $options, $cacheResource);
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

        $cacheResource = new CacheResource();
        $cacheResource->setBreakpoints($options, $value);

        return $this->resourceCache->update($publicId, $options, $cacheResource);
    }

    /**
     * @param $publicId
     * @param $options
     *
     * @return array
     */
    public function get($publicId, $options)
    {
        if (!$this->enabled()) {
            return null;
        }

        $cacheResource = $this->resourceCache->get($publicId, $options);

        if (is_null($cacheResource)) {
            return null;
        }

        return $cacheResource->getBreakpoints($options);
    }

    /**
     * @param $publicId
     * @param $options
     *
     * @return null
     */
    public function delete($publicId, $options)
    {
        return $this->resourceCache->delete($publicId, $options);
    }

    /**
     *
     */
    public function clear()
    {
        return $this->resourceCache->clear();
    }
}
