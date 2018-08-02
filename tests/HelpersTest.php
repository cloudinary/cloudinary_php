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
    protected static $fetch_path;

    protected static $mocked_response = '{"breakpoints":[50,500,1000]}';
    protected static $mocked_breakpoints = [50, 500, 1000];
    protected static $expected_transformation = "c_scale,w_auto:breakpoints_50_1000_20_20:json";

    protected static $crop_transformation = ['crop' => 'crop', 'width' => 100];
    protected static $crop_transformation_str = 'c_crop,w_100';


    public static function setUpBeforeClass()
    {
        \Cloudinary::reset_config();

        if (!Cloudinary::config_get("api_secret")) {
            self::markTestSkipped('Please setup environment for Helpers test to run');
        }

        $cloud_name = Cloudinary::config_get("cloud_name");
        self::$fetch_path = "http://res.cloudinary.com/$cloud_name/image/fetch";

        self::$helpers_test_id = "helpers_test_" . UNIQUE_TEST_ID;

        Uploader::upload(TEST_IMG, ["public_id" => self::$helpers_test_id, "tags" => array(TEST_TAG, UNIQUE_TEST_TAG)]);
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
    public function test_fetch_breakpoints()
    {
        Curl::mockRequest($this, self::$mocked_response);

        $actual_breakpoints = fetch_breakpoints(self::$helpers_test_id);

        $this->assertEquals(self::$mocked_breakpoints, $actual_breakpoints);

        $this->assertContains(self::$expected_transformation, Curl::$instance->url_path());
    }

    /**
     * Should retrieve responsive breakpoints from cloudinary resource with custom stransformation (mocked)
     *
     * @throws \Cloudinary\Error
     */
    public function test_fetch_breakpoints_with_transformation()
    {
        Curl::mockRequest($this, self::$mocked_response);

        $srcset = ["transformation" => self::$crop_transformation];
        $actual_breakpoints = fetch_breakpoints(self::$helpers_test_id, $srcset);

        $this->assertEquals(self::$mocked_breakpoints, $actual_breakpoints);

        $this->assertContains(
            self::$crop_transformation_str . '/' .self::$expected_transformation,
            Curl::$instance->url_path()
        );
    }

    /**
     * Should retrieve responsive breakpoints from cloudinary resource (real request)
     *
     * @throws \Cloudinary\Error
     */
    public function test_fetch_breakpoints_real()
    {
        $actual_breakpoints = fetch_breakpoints(self::$helpers_test_id);

        $this->assertContains(self::$expected_transformation, Curl::$instance->url_path());

        $this->assertTrue(is_array($actual_breakpoints));
        $this->assertGreaterThan(0, count($actual_breakpoints));
    }

    /**
     * Should correctly handle format and fetch_format in srcset url with and without custom transformation
     */
    public function test_generate_single_srcset_url_fetch_format()
    {
        $fetch_resource_url = "http://cloudinary.com/images/logo.png";
        $image_format = "jpg";
        $fetch_format = "gif";
        $resp_w = 99;
        $resp_trans = "c_scale,w_$resp_w";
        $effect = "sepia";
        $raw_transformation = "c_fill,e_grayscale,q_auto";

        $options = array("format" => $image_format, "type" => "fetch", "fetch_format" => $fetch_format);

        // Without custom transformation
        $actual_url = generate_single_srcset_url($fetch_resource_url, $resp_w, [], $options);

        $this->assertEquals(
            self::$fetch_path . "/f_$fetch_format/$resp_trans/$fetch_resource_url",
            $actual_url
        );

        // With custom transformation
        $actual_url = generate_single_srcset_url($fetch_resource_url, $resp_w, self::$crop_transformation, $options);

        $this->assertEquals(
            self::$fetch_path . "/c_crop,f_$image_format,w_100/$resp_trans/$fetch_resource_url",
            $actual_url
        );

        // Add base transformation
        $options["effect"] = $effect;
        $actual_url = generate_single_srcset_url($fetch_resource_url, $resp_w, [], $options);

        $this->assertEquals(
            self::$fetch_path . "/e_$effect,f_$fetch_format/$resp_trans/$fetch_resource_url",
            $actual_url
        );

        // Should ignore base transformation
        $actual_url = generate_single_srcset_url($fetch_resource_url, $resp_w, self::$crop_transformation, $options);

        $this->assertEquals(
            self::$fetch_path . "/c_crop,f_$image_format,w_100/$resp_trans/$fetch_resource_url",
            $actual_url
        );

        $options["raw_transformation"] = $raw_transformation;

        // Should include raw transformation from base options
        $actual_url = generate_single_srcset_url($fetch_resource_url, $resp_w, [], $options);

        $this->assertEquals(
            self::$fetch_path . "/e_$effect,f_$fetch_format,$raw_transformation/$resp_trans/$fetch_resource_url",
            $actual_url
        );
    }

}
