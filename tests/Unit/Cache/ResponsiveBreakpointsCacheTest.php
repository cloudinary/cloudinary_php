<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Cache;

use Cloudinary\Asset\Image;
use Cloudinary\Cache\Adapter\KeyValueCacheAdapter;
use Cloudinary\Cache\ResponsiveBreakpointsCache;
use Cloudinary\Cache\Storage\FileSystemKeyValueStorage;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Test\Unit\Cache\Storage\DummyCacheStorage;
use Cloudinary\Test\Unit\UnitTestCase;
use Exception;
use Monolog\Logger as Monolog;
use PHPUnit\Framework\Assert;
use Prophecy\Exception\InvalidArgumentException;
use ReflectionClass;
use ReflectionException;

/**
 * Class ResponsiveBreakpointsCacheTest
 */
final class ResponsiveBreakpointsCacheTest extends UnitTestCase
{

    protected static $publicId;
    protected static $breakpoints = [100, 200, 300, 399];

    /**
     * @var ResponsiveBreakpointsCache
     */
    protected $cache;

    /**
     * @var Image $image Test image that is commonly reused by tests
     */
    protected $image;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$publicId = self::$UNIQUE_TEST_ID;
    }

    public function setUp()
    {
        parent::setUp();

        $this->image = new Image(self::$publicId);

        $this->cache = ResponsiveBreakpointsCache::instance();
        $this->cache->setCacheAdapter(new KeyValueCacheAdapter(new DummyCacheStorage()));
    }

    public function testRBCacheInstance()
    {
        $instance  = ResponsiveBreakpointsCache::instance();
        $instance2 = ResponsiveBreakpointsCache::instance();

        $this::assertEquals($instance, $instance2);
    }

    public function testRBCacheSetGet()
    {
        $this->cache->set($this->image, self::$breakpoints);

        $res = $this->cache->get($this->image);

        $this::assertEquals(self::$breakpoints, $res);
    }

    /**
     * Integration test
     */
    public function testRBCacheFetchBreakpointsFromCloudinary()
    {
        self::markTestSkipped('Skip integration test');
        $actualBreakpoints = $this->cache->get(new Image(AssetTestCase::IMAGE_NAME), true);

        $this->assertInternalType('array', $actualBreakpoints);
        $this->assertGreaterThan(0, count($actualBreakpoints));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testRBCacheSetInvalidBreakpoints()
    {
        $this->cache->set($this->image, 'Not breakpoints at all');
    }

    public function testRBCacheDelete()
    {
        $this->cache->set($this->image, self::$breakpoints);

        $this->cache->delete($this->image);

        $res = $this->cache->get($this->image);

        $this::assertNull($res);
    }


    public function testRBCacheFlushAll()
    {
        $this->cache->set($this->image, self::$breakpoints);

        $this->cache->flushAll();

        Assert::assertNull($this->cache->get($this->image));
    }

    /**
     * @throws ReflectionException
     */
    public function testRBCacheDisabled()
    {
        $disabledCache = ResponsiveBreakpointsCache::instance();

        $disabledCacheReflection = new ReflectionClass($disabledCache);

        $cacheAdapterProperty = $disabledCacheReflection->getProperty('cacheAdapter');
        $cacheAdapterProperty->setAccessible(true);
        $cacheAdapterProperty->setValue($disabledCache, null);

        Assert::assertFalse($disabledCache->enabled());

        Assert::assertFalse($disabledCache->set($this->image, self::$breakpoints));
        Assert::assertNull($disabledCache->get($this->image));
        Assert::assertFalse($disabledCache->delete($this->image));
        Assert::assertFalse($disabledCache->flushAll());
    }

    public function testRBCacheFileSystemStorage()
    {
        $this->cache->init(
            [
                'responsiveBreakpoints' => [
                    'cache_adapter' => new KeyValueCacheAdapter(new FileSystemKeyValueStorage(null)),
                ],
            ]
        );

        $res = null;

        try {
            $this->cache->set($this->image, self::$breakpoints);
            $res = $this->cache->get($this->image);
        } catch (Exception $e) {
            unset($e);
        } finally {
            $this->cache->delete($this->image);
        }

        $this::assertEquals(self::$breakpoints, $res);
    }

    /**
     * @throws ReflectionException
     */
    public function testInvalidResponsiveBreakpointsCacheSetValue()
    {
        $message = null;

        $responsiveBreakpointsCache = new ResponsiveBreakpointsCache();
        $responsiveBreakpointsCache->setCacheAdapter(new KeyValueCacheAdapter(new FileSystemKeyValueStorage('/')));

        try {
            $responsiveBreakpointsCache->set(new Image(''), '');
        } catch (\InvalidArgumentException $e) {
            $message = $e->getMessage();
        }

        self::assertEquals('An array of breakpoints is expected', $message);
        self::assertObjectLoggedMessage($responsiveBreakpointsCache, $message, Monolog::CRITICAL);
    }
}
