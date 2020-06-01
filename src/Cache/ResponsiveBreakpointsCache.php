<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Cache;

use Cloudinary\ArrayUtils;
use Cloudinary\Asset\Image;
use Cloudinary\Cache\Adapter\CacheAdapter;
use Cloudinary\ClassUtils;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\ResponsiveBreakpointsConfig;
use Cloudinary\Exception\Error;
use Cloudinary\HttpClient;
use Cloudinary\Log\LoggerTrait;
use Cloudinary\Transformation\Parameter\Misc\BreakpointsJson;
use Exception;
use InvalidArgumentException;

/**
 * Caches breakpoint values for image assets.
 *
 * @api
 */
class ResponsiveBreakpointsCache
{
    use LoggerTrait;

    /**
     * @var bool|ResponsiveBreakpointsCache $instance The instance of the cache.
     */
    private static $instance = false;

    /**
     * @var CacheAdapter The cache adapter used to store and retrieve values.
     */
    protected $cacheAdapter;

    /**
     * @var ResponsiveBreakpointsConfig $config The configuration of the cache.
     */
    protected $config;

    /**
     * @var HttpClient $client The HTTP client.
     */
    protected $client;

    /**
     * ResponsiveBreakpointsCache constructor.
     *
     * @param Configuration|null $configuration
     *
     * @api
     */
    public function __construct($configuration = null)
    {
        $this->client = new HttpClient($configuration);

        $this->init($configuration);
    }

    /**
     * Returns a singleton instance of the cache.
     *
     * @param Configuration|null $config Optional configuration to pass during the first initialization.
     *
     * @return bool|ResponsiveBreakpointsCache
     */
    public static function instance($config = null)
    {
        if (self::$instance === false) {
            self::$instance = new static($config);
        }

        return self::$instance;
    }

    /**
     * Initializes the cache.
     *
     * @param Configuration|array|null $configuration
     */
    public function init($configuration = null)
    {
        if ($configuration === null) {
            $configuration = Configuration::instance(); // get global instance
        }

        /** @noinspection CallableParameterUseCaseInTypeContextInspection */
        $configuration = ClassUtils::verifyInstance($configuration, Configuration::class);

        $this->config = new ResponsiveBreakpointsConfig($configuration->responsiveBreakpoints);
        $this->logging = $configuration->logging;

        $this->setCacheAdapter($this->config->cacheAdapter);
    }

    /**
     * Assigns cache adapter.
     *
     * @param CacheAdapter $cacheAdapter The cache adapter used to store and retrieve values.
     *
     * @return bool Returns true if the $cacheAdapter is valid
     */
    public function setCacheAdapter($cacheAdapter)
    {
        if ($cacheAdapter === null || ! $cacheAdapter instanceof CacheAdapter) {
            return false;
        }

        $this->cacheAdapter = $cacheAdapter;

        return true;
    }

    /**
     * Indicates whether cache is enabled or not.
     *
     * @return bool true if a cache adapter has been set.
     */
    public function enabled()
    {
        return $this->cacheAdapter !== null;
    }

    /**
     * Extracts the parameters required in order to calculate the key of the cache.
     *
     * @param Image $asset
     *
     * @return array A list of values used to calculate the cache key.
     */
    private static function extractParameters($asset)
    {
        $json = $asset->jsonSerialize();

        $publicId       = $asset->getPublicId(true);
        $transformation = $asset->getTransformation();
        $format         = ArrayUtils::get($json, ['asset', 'extension']);
        $type           = ArrayUtils::get($json, ['asset', 'delivery_type']);
        $resourceType   = ArrayUtils::get($json, ['asset', 'asset_type']);

        return [$publicId, $type, $resourceType, $transformation, $format];
    }


    /**
     * Fetches breakpoints from Cloudinary.
     *
     * @param Image $asset
     *
     * @return array
     * @throws Error
     */
    protected function fetchBreakpoints($asset)
    {
        $c                    = $this->config;
        $breakpointsJsonParam = new BreakpointsJson($c->minWidth, $c->minWidth, $c->bytesStep, $c->maxImages);

        $breakpointsUrl = $asset->toUrl($breakpointsJsonParam);

        return $this->client->getJson($breakpointsUrl)['breakpoints'];
    }

    /**
     * Retrieve the breakpoints of a particular derived resource identified by the $publicId and $options
     *
     * @param Image     $asset
     *
     * @param bool|null $fetchMissing Indicates whether try to bring missing breakpoints from Cloudinary
     *
     * @return array|null Array of responsive breakpoints, null if not found
     */
    public function get($asset, $fetchMissing = null)
    {
        if (! $this->enabled()) {
            return null;
        }

        $breakpoints = $this->cacheAdapter->get(...self::extractParameters($asset));

        if ($breakpoints !== null) {
            return $breakpoints;
        }

        // Cache miss :(

        if ($fetchMissing === null) {
            $fetchMissing = $this->config->fetchMissing;
        }

        if ($fetchMissing !== true) {
            return null; // no fetch - no breakpoints :)
        }

        try {
            $breakpoints = $this->fetchBreakpoints($asset);
        } catch (Exception $e) {
            error_log("Failed fetching responsive breakpoints: $e");
        }

        if ($breakpoints !== null) {
            $this->set($asset, $breakpoints); // Keep fetched breakpoints
        }

        return $breakpoints;
    }

    /**
     * Sets responsive breakpoints identified by public ID and options
     *
     * @param Image $asset The asset.
     * @param array $value Array of responsive breakpoints to set
     *
     * @return bool true on success or false on failure
     */
    public function set($asset, $value = [])
    {
        if (! $this->enabled()) {
            return false;
        }

        if (! is_array($value)) {
            $message = 'An array of breakpoints is expected';
            $this->getLogger()->critical($message, ['class' => static::class, 'value' => $value]);
            throw new InvalidArgumentException($message);
        }

        $params    = self::extractParameters($asset);
        $params [] = $value;

        return $this->cacheAdapter->set(...$params);
    }

    /**
     * Delete responsive breakpoints identified by public ID and options
     *
     * @param Image $asset The asset.
     *
     * @return bool true on success or false on failure
     */
    public function delete($asset)
    {
        if (! $this->enabled()) {
            return false;
        }

        return $this->cacheAdapter->delete(...self::extractParameters($asset));
    }

    /**
     * Flushes all entries from the cache
     *
     * @return bool true on success or false on failure
     */
    public function flushAll()
    {
        if (! $this->enabled()) {
            return false;
        }

        return $this->cacheAdapter->flushAll();
    }
}
