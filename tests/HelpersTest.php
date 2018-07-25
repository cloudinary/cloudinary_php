<?php

namespace Cloudinary;

use Cloudinary;
use PHPUnit\Framework\TestCase;

/**
 * Class HelpersTest
 * @package Cloudinary
 */
class HelpersTest extends TestCase
{
    protected static $helpers_test_id;

    public static function setUpBeforeClass()
    {
        \Cloudinary::reset_config();

        if (!Cloudinary::config_get("api_secret")) {
            self::markTestSkipped('Please setup environment for Helpers test to run');
        }

        self::$helpers_test_id = "helpers_test_" . UNIQUE_TEST_ID;

        Uploader::upload(TEST_IMG, ["public_id" => self::$helpers_test_id]);
    }

    public static function tearDownAfterClass()
    {
        if (!Cloudinary::config_get("api_secret")) {
            self::fail("You need to configure the cloudinary api for the tests to work.");
        }

        $api = new Cloudinary\Api();

        try {
            $api->delete_resources([self::$helpers_test_id]);
        } catch (\Exception $e) {
        }
    }

    public function setUp()
    {
        Curl::$instance = new Curl();
    }

    /**
     * Should retrieve responsive breakpoints from cloudinary resource (mocked)
     *
     * @throws \Cloudinary\Error
     */
    public function test_get_responsive_breakpoints_from_cloudinary()
    {
        Curl::mockRequest($this, '{"breakpoints":[50,500,1000]}');

        $actual_breakpoints = get_responsive_breakpoints_from_cloudinary(self::$helpers_test_id);

        $this->assertEquals([50, 500, 1000], $actual_breakpoints);

        $this->assertContains("w_auto:breakpoints_50_1000_20_20:json", Curl::$instance->url_path());
    }

    /**
     * Should retrieve responsive breakpoints from cloudinary resource (real request)
     *
     * @throws \Cloudinary\Error
     */
    public function test_get_responsive_breakpoints_from_cloudinary_real()
    {
        $actual_breakpoints = get_responsive_breakpoints_from_cloudinary(self::$helpers_test_id);

        $this->assertContains("w_auto:breakpoints_50_1000_20_20:json", Curl::$instance->url_path());

        $this->assertTrue(is_array($actual_breakpoints));
        $this->assertGreaterThan(0, count($actual_breakpoints));
    }

}
