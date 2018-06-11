<?php

namespace Cloudinary\Test;

use Cloudinary\Cache\ResponsiveBreakpointsCache;
use Cloudinary\Curl;
use Cloudinary\Uploader;
use Cloudinary\Test\Helpers\DummyCacheConnector;
use PHPUnit\Framework\TestCase;

require_once('TestHelper.php');

/**
 * Class ResponsiveBreakpointsCacheTest
 */
class ResponsiveBreakpointsCacheTest extends TestCase
{
    protected static $cache;

    protected static $transformation_1 = [
        "angle" => 45,
        "crop" => "scale"
    ];
    protected static $transforamtion_1_rb = [206, 50];
    protected static $rb_params;

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
                    "format" => 'png'
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
        self::$cache = ResponsiveBreakpointsCache::instance();
        self::$cache->setCacheConnector(new DummyCacheConnector());
    }

    public function testRBCacheInstance()
    {
        $instance = ResponsiveBreakpointsCache::instance();
        $instance2 = ResponsiveBreakpointsCache::instance();
        $this::assertEquals($instance, $instance2);
    }
    public function testRBCacheSetGet()
    {
        $cache = ResponsiveBreakpointsCache::instance();

        $cache->set("dummy", [], "dummy_val");
        $res = $cache->get("dummy", []);
        $this::assertEquals("dummy_val", $res);
    }
    public function testRBCacheUpload()
    {
        $result = Uploader::upload(\Cloudinary\TEST_IMG, self::$rb_params);

        $res = self::$cache->get($result["public_id"], ["transformation" => self::$transformation_1]);
        $this::assertEquals(self::$transforamtion_1_rb, $res);
    }

    public function testRBCacheExplicit()
    {
        $upResult = Uploader::upload(\Cloudinary\TEST_IMG);

        $result = Uploader::explicit($upResult["public_id"], self::$rb_params);

        $res = self::$cache->get($result["public_id"], ["transformation" => self::$transformation_1]);
        $this::assertEquals(self::$transforamtion_1_rb, $res);
    }
    public function testRBCacheCustomConnector()
    {
        $customCache = ResponsiveBreakpointsCache::instance();
        $customCache->init(["cache" => new DummyCacheConnector()]);

        $customCache->set("dummy", [], "dummy_val");
        $res = $customCache->get("dummy", []);
        $this::assertEquals("dummy_val", $res);
    }
}
