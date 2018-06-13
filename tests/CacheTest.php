<?php

namespace Cloudinary\Test;

use Cloudinary\Cache\Adapter\KeyValueCacheAdapter;
use Cloudinary\Cache\ResponsiveBreakpointsCache;
use Cloudinary\Cache\Storage\FileSystemKeyValueStorage;
use Cloudinary\Curl;
use Cloudinary\Uploader;
use Cloudinary\Test\Cache\Storage\DummyCacheStorage;
use Exception;
use PHPUnit\Framework\TestCase;

require_once('TestHelper.php');

/**
 * Class ResponsiveBreakpointsCacheTest
 */
class ResponsiveBreakpointsCacheTest extends TestCase
{

    protected $cache;

    protected static $publicId = "dummy";
    protected static $breakpoints = [5,3,7,5];

    protected static $transformation_1 = ["angle" => 45, "crop" => "scale"];
    protected static $format_1 = "png";
    protected static $transforamtion_1_rb = [206, 50];

    protected static $rb_params;

    /**
     * ResponsiveBreakpointsCacheTest constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->cache = ResponsiveBreakpointsCache::instance();
    }


    public static function setUpBeforeClass()
    {
        Curl::$instance = new Curl();

        self::$rb_params = [
            "responsive_breakpoints" => [
                [
                    "create_derived" => false,
                    "transformation" => [
                        "angle" => 90
                    ],
                    "format" => 'gif'
                ],
                [
                    "create_derived" => false,
                    "transformation" => self::$transformation_1,
                    "format" => self::$format_1
                ],
                [
                    "create_derived" => false
                ]
            ],
            "type" => "upload"
        ];
    }

    public function setUp()
    {
        $this->cache->setCacheAdapter(new KeyValueCacheAdapter(new DummyCacheStorage()));
    }

    public function testRBCacheInstance()
    {
        $instance = ResponsiveBreakpointsCache::instance();
        $instance2 = ResponsiveBreakpointsCache::instance();
        $this::assertEquals($instance, $instance2);
    }

    public function testRBCacheSetGet()
    {
        $this->cache->set(self::$publicId, [], self::$breakpoints);

        $res = $this->cache->get(self::$publicId);

        $this::assertEquals(self::$breakpoints, $res);
    }

    public function testRBCacheUpload()
    {
        $result = Uploader::upload(\Cloudinary\TEST_IMG, self::$rb_params);

        $res = $this->cache->get(
            $result["public_id"],
            ["transformation" => self::$transformation_1, "format" => self::$format_1]
        );

        $this::assertEquals(self::$transforamtion_1_rb, $res);
    }

    public function testRBCacheExplicit()
    {
        $upResult = Uploader::upload(\Cloudinary\TEST_IMG);

        $result = Uploader::explicit($upResult["public_id"], self::$rb_params);

        $res = $this->cache->get(
            $result["public_id"],
            ["transformation" => self::$transformation_1, "format" => self::$format_1]
        );

        $this::assertEquals(self::$transforamtion_1_rb, $res);
    }

    public function testRBCacheImagetag()
    {
        $upResult = Uploader::upload(\Cloudinary\TEST_IMG);

        $image_tag = cl_image_tag($upResult["public_id"], ["transformation" => self::$transformation_1,
                                                           "format" => self::$format_1,
                                                           "srcset"=> ["rb_cache_enabled" => true]]);
    }

    public function testRBCacheFileSystemStorage()
    {
        $this->cache->init(["cache_adapter" => new KeyValueCacheAdapter(new FileSystemKeyValueStorage(null))]);
        try {
            $this->cache->set(self::$publicId, [], self::$breakpoints);
            $res = $this->cache->get(self::$publicId);
        } catch (Exception $e) {
            unset($e);
        }
        // No finally in PHP 5.4
        $this->cache->delete("dummy");

        $this::assertEquals(self::$breakpoints, $res);
    }
}
