<?php namespace Cloudinary\Cache;

use Cloudinary\Cache\Adapter\CacheAdapter;
use Cloudinary\Utils\Singleton;
use InvalidArgumentException;

/**
 * Class ResponsiveBreakpointsCache
 * @package Cloudinary\Cache
 */
class ResponsiveBreakpointsCache extends Singleton
{

    /**
     * @var CacheAdapter
     */
    protected $cacheAdapter;

    /**
     * ResponsiveBreakpointsCache constructor.
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * @param array $cacheOptions
     */
    public function init($cacheOptions = array())
    {
        $cacheAdapter = \Cloudinary::option_get($cacheOptions, "cache_adapter");

        $this->setCacheAdapter($cacheAdapter);
    }

    /**
     * Assigns cache adapter
     *
     * @param CacheAdapter $cacheAdapter
     *
     * @return bool indicating whether adapter is set
     */
    public function setCacheAdapter($cacheAdapter)
    {
        if (is_null($cacheAdapter) || ! $cacheAdapter instanceof CacheAdapter) {
            return false;
        }

        $this->cacheAdapter = $cacheAdapter;

        return true;
    }

    /**
     * Indicates whether cache is enabled or not
     *
     * @return bool
     */
    public function enabled()
    {
        return !is_null($this->cacheAdapter);
    }

    /**
     * Helper method. Returns a list of parameters extracted from options
     *
     * @param array $options Input options
     *
     * @return array Extracted parameters
     */
    private static function optionsToParameters($options)
    {
        $optionsCopy = \Cloudinary::array_copy($options);
        $transformation = \Cloudinary::generate_transformation_string($optionsCopy);
        $format = \Cloudinary::option_get($options, "format", "");
        $type = \Cloudinary::option_get($options, "type", "upload");
        $resourceType = \Cloudinary::option_get($options, "resource_type", "image");

        return [$type, $resourceType, $transformation, $format];
    }

    /**
     * Gets responsive breakpoints identified by public ID and options
     *
     * @param string $publicId  The public ID of the resource
     * @param array  $options   Additional options
     *
     * @return array|null Array of responsive breakpoints, null if not found
     */
    public function get($publicId, $options = [])
    {
        if (!$this->enabled()) {
            return null;
        }

        list($type, $resourceType, $transformation, $format) = self::optionsToParameters($options);

        return $this->cacheAdapter->get($publicId, $type, $resourceType, $transformation, $format);
    }

    /**
     * Sets responsive breakpoints identified by public ID and options
     *
     * @param string  $publicId The public ID of the resource
     * @param array   $options  Additional options
     * @param array   $value    Array of responsive breakpoints to set
     *
     * @return bool true on success or false on failure
     */
    public function set($publicId, $options = [], $value = [])
    {
        if (!$this->enabled()) {
            return false;
        }

        if (! is_array($value)) {
            throw new InvalidArgumentException("An array of breakpoints is expected");
        }

        list($type, $resourceType, $transformation, $format) = self::optionsToParameters($options);

        return $this->cacheAdapter->set($publicId, $type, $resourceType, $transformation, $format, $value);
    }

    /**
     * Deletes responsive breakpoints identified by public ID and options
     *
     * @param string  $publicId The public ID of the resource
     * @param array   $options  Additional options
     *
     * @return bool true on success or false on failure
     */
    public function delete($publicId, $options = [])
    {
        if (!$this->enabled()) {
            return false;
        }

        list($type, $resourceType, $transformation, $format) = self::optionsToParameters($options);

        return $this->cacheAdapter->delete($publicId, $type, $resourceType, $transformation, $format);
    }

    /**
     * Flushes all entries from cache
     *
     * @return bool true on success or false on failure
     */
    public function flushAll()
    {
        if (!$this->enabled()) {
            return false;
        }

        return $this->cacheAdapter->flushAll();
    }
}
