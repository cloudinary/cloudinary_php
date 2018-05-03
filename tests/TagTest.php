<?php
$base = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..');

use PHPUnit\Framework\TestCase;

require_once(join(DIRECTORY_SEPARATOR, array($base, 'src', 'Cloudinary.php')));

class TagTest extends TestCase
{
    const DEFAULT_UPLOAD_PATH = 'http://res.cloudinary.com/test123/image/upload/';
    const VIDEO_UPLOAD_PATH = 'http://res.cloudinary.com/test123/video/upload/';
    private static $public_id;
    private static $common_image_options;
    private static $common_transformation_str;
    private static $breakpoints_arr;
    private static $last_breakpoint;
    private static $common_srcset;
    private static $custom_attributes;

    public function setUp()
    {
        Cloudinary::reset_config();
        Cloudinary::config(
            array(
                "cloud_name" => "test123",
                "api_key" => "a",
                "api_secret" => "b",
                "secure_distribution" => null,
                "private_cdn" => false,
                "cname" => null
            )
        );
        self::$public_id = 'sample.jpg';
        self::$common_image_options = array(
            'effect' => 'sepia',
            'cloud_name' => 'test123',
            'client_hints' => false,
        );

        self::$common_transformation_str = 'e_sepia';
        self::$breakpoints_arr = array(100, 200, 300, 399);
        self::$last_breakpoint = self::$breakpoints_arr[count(self::$breakpoints_arr)-1];
        self::$common_srcset = array('breakpoints' => self::$breakpoints_arr);
        self::$custom_attributes = array('custom_attr1' => 'custom_value1', 'custom_attr2' => 'custom_value2');
    }

    public function test_cl_image_tag()
    {
        $tag = cl_image_tag("test", array("width" => 10, "height" => 10, "crop" => "fill", "format" => "png"));
        $this->assertEquals(
            "<img src='" . self::DEFAULT_UPLOAD_PATH . "c_fill,h_10,w_10/test.png' height='10' width='10'/>",
            $tag
        );
    }

    /**
     * Check that cl_image_tag encodes special characters.
     */
    public function test_cl_image_tag_special_characters_encoding()
    {
        $tag = cl_image_tag(
            "test's special < \"characters\" >",
            array("width" => 10, "height" => 10, "crop" => "fill", "format" => "png", "alt" => "< test's > special \"")
        );
        $expected = "<img src='" . self::DEFAULT_UPLOAD_PATH . "c_fill,h_10,w_10/" .
            "test%27s%20special%20%3C%20%22characters%22%20%3E.png'" .
            " alt='&lt; test&#039;s &gt; special &quot;' height='10' width='10'/>";

        $this->assertEquals($expected, $tag);
    }

    public function test_responsive_width()
    {
        // should add responsive width transformation
        $tag = cl_image_tag("hello", array("responsive_width" => true, "format" => "png"));
        $this->assertEquals(
            "<img class='cld-responsive' data-src='" . self::DEFAULT_UPLOAD_PATH . "c_limit,w_auto/hello.png'/>",
            $tag
        );

        $options = array("width" => 100, "height" => 100, "crop" => "crop", "responsive_width" => true);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals($options, array("responsive" => true));
        $this->assertEquals($result, TagTest::DEFAULT_UPLOAD_PATH . "c_crop,h_100,w_100/c_limit,w_auto/test");
        Cloudinary::config(
            array(
                "responsive_width_transformation" => array(
                    "width" => "auto:breakpoints",
                    "crop" => "pad",
                ),
            )
        );
        $options = array("width" => 100, "height" => 100, "crop" => "crop", "responsive_width" => true);
        $result = Cloudinary::cloudinary_url("test", $options);
        $this->assertEquals($options, array("responsive" => true));
        $this->assertEquals(
            $result,
            TagTest::DEFAULT_UPLOAD_PATH . "c_crop,h_100,w_100/c_pad,w_auto:breakpoints/test"
        );
    }

    public function test_width_auto()
    {
        // should support width=auto
        $tag = cl_image_tag("hello", array("width" => "auto", "crop" => "limit", "format" => "png"));
        $this->assertEquals(
            "<img class='cld-responsive' data-src='" . self::DEFAULT_UPLOAD_PATH . "c_limit,w_auto/hello.png'/>",
            $tag
        );
        $tag = cl_image_tag("hello", array("width" => "auto:breakpoints", "crop" => "limit", "format" => "png"));
        $this->assertEquals(
            "<img class='cld-responsive' data-src='" . self::DEFAULT_UPLOAD_PATH . "c_limit,w_auto:breakpoints/hello.png'/>",
            $tag
        );
        $this->cloudinary_url_assertion(
            "test",
            array("width" => "auto:20", "crop" => 'fill'),
            TagTest::DEFAULT_UPLOAD_PATH . "c_fill,w_auto:20/test",
            array('responsive' => true)
        );
        $this->cloudinary_url_assertion(
            "test",
            array("width" => "auto:20:350", "crop" => 'fill'),
            TagTest::DEFAULT_UPLOAD_PATH . "c_fill,w_auto:20:350/test",
            array('responsive' => true)
        );
        $this->cloudinary_url_assertion(
            "test",
            array("width" => "auto:breakpoints", "crop" => 'fill'),
            TagTest::DEFAULT_UPLOAD_PATH . "c_fill,w_auto:breakpoints/test",
            array('responsive' => true)
        );
        $this->cloudinary_url_assertion(
            "test",
            array("width" => "auto:breakpoints_100_1900_20_15", "crop" => 'fill'),
            TagTest::DEFAULT_UPLOAD_PATH . "c_fill,w_auto:breakpoints_100_1900_20_15/test",
            array('responsive' => true)
        );
        $this->cloudinary_url_assertion(
            "test",
            array("width" => "auto:breakpoints:json", "crop" => 'fill'),
            TagTest::DEFAULT_UPLOAD_PATH . "c_fill,w_auto:breakpoints:json/test",
            array('responsive' => true)
        );
    }

    public function test_initial_width_and_height()
    {
        $options = array("crop" => "crop", "width" => "iw", "height" => "ih");
        $this->cloudinary_url_assertion(
            "test",
            $options,
            TagTest::DEFAULT_UPLOAD_PATH . "c_crop,h_ih,w_iw/test"
        );
    }

    public function shared_client_hints($options, $message = '')
    {
        $tag = cl_image_tag('sample.jpg', $options);
        $this->assertEquals(
            "<img src='http://res.cloudinary.com/test/image/upload/c_scale,dpr_auto,w_auto/sample.jpg' />",
            $tag,
            $message
        );
        $tag = cl_image_tag('sample.jpg', array_merge(array("responsive" => true), $options));
        $this->assertEquals(
            "<img src='http://res.cloudinary.com/test/image/upload/c_scale,dpr_auto,w_auto/sample.jpg' />",
            $tag,
            $message
        );
    }

    public function test_client_hints_as_option()
    {
        $this->shared_client_hints(
            array(
                "dpr" => "auto",
                "cloud_name" => "test",
                "width" => "auto",
                "crop" => "scale",
                "client_hints" => true,
            ),
            "support client_hints as an option"
        );
    }

    public function test_client_hints_as_global()
    {
        Cloudinary::config(array("client_hints" => true));
        $this->shared_client_hints(
            array(
                "dpr" => "auto",
                "cloud_name" => "test",
                "width" => "auto",
                "crop" => "scale",
            ),
            "support client hints as global configuration"
        );
    }

    public function test_client_hints_false()
    {
        Cloudinary::config(array("responsive" => true));
        $tag = cl_image_tag(
            'sample.jpg',
            array(
                "width" => "auto",
                "crop" => "scale",
                "cloud_name" => "test123",
                "client_hints" => false,
            )
        );
        $this->assertEquals(
            "<img class='cld-responsive' data-src='" . TagTest::DEFAULT_UPLOAD_PATH . "c_scale,w_auto/sample.jpg'/>",
            $tag,
            "should use normal responsive behaviour"
        );
    }


    /**
     * Helper method for test_cl_image_tag_srcset for generating expected image tag
     *
     * @param string $public_id Public ID of the image
     * @param string $common_trans_str Default transformation string to be used in all resources
     * @param string $custom_trans_str Optional custom transformation string to be be used inside srcset resources
     *                                      If not provided, $common_trans_str is used
     * @param array $srcset_breakpoints Optional list of breakpoints for srcset. If not provided srcset is omitted
     * @param array $attributes Associative array of custom attributes to be added to the tag
     *
     * @return string Resulting image tag
     */

    private function get_expected_cl_image_tag(
        $public_id,
        $common_trans_str,
        $custom_trans_str = '',
        $srcset_breakpoints = array(),
        $attributes = array()
    )
    {
        if (empty($custom_trans_str)) {
            $custom_trans_str = $common_trans_str;
        }

        if (!empty($srcset_breakpoints)) {
            $single_srcset_image = function ($w) use ($custom_trans_str, $public_id) {
                return TagTest::DEFAULT_UPLOAD_PATH . "{$custom_trans_str}/c_scale,w_{$w}/{$public_id} {$w}w";
            };
            $attributes['srcset'] = implode(', ', array_map($single_srcset_image, $srcset_breakpoints));
        }

        $tag = "<img src='" . TagTest::DEFAULT_UPLOAD_PATH . "{$common_trans_str}/{$public_id }'";

        $attributes_str = implode(
            ' ',
            array_map(
                function ($k, $v) {
                    return "$k='$v'";
                },
                array_keys($attributes),
                array_values($attributes)
            )
        );

        if (!empty($attributes_str)) {
            $tag .= " {$attributes_str}";
        }

        $tag .= "/>";
        if (getenv('DEBUG')) {
            echo preg_replace('/([,\']) /', "$1\n    ", $tag) . "\n\n";
        }
        return $tag;
    }

    public function test_cl_image_tag_srcset()
    {
        // Should create srcset attribute with provided breakpoints
        $tag_with_breakpoints = cl_image_tag(
            self::$public_id,
            array_merge(
                self::$common_image_options,
                array('srcset' => self::$common_srcset)
            )
        );

        $expected_tag = self::get_expected_cl_image_tag(self::$public_id, self::$common_transformation_str, '', self::$breakpoints_arr);
        $this->assertEquals($expected_tag, $tag_with_breakpoints,
            'Should create srcset attribute with provided breakpoints');

    }

    public function test_support_srcset_attribute_defined_by_min_width_max_width_and_max_images()
    {
        $tag_min_max_count = cl_image_tag(
            self::$public_id,
            array_merge(
                self::$common_image_options,
                array('srcset' => array(
                    'min_width' => self::$breakpoints_arr[0],
                    'max_width' => $x = self::$last_breakpoint,
                    'max_images' => count(self::$breakpoints_arr)))
            )
        );

        $expected_tag = self::get_expected_cl_image_tag(self::$public_id, self::$common_transformation_str, '', self::$breakpoints_arr);

        $this->assertEquals($expected_tag, $tag_min_max_count,
            'Should support srcset attribute defined by min_width, max_width, and max_images');

        // Should support 1 image in srcset
        $tag_one_image_by_params = cl_image_tag(
            self::$public_id,
            array_merge(
                self::$common_image_options,
                array('srcset' => array('min_width' => self::$breakpoints_arr[0],
                                        'max_width' => self::$last_breakpoint,
                                        'max_images' => 1))
            )
        );

        $expected_1_image_tag = self::get_expected_cl_image_tag(
            self::$public_id,
            self::$common_transformation_str,
            '',
            array(self::$last_breakpoint)
        );

        $this->assertEquals($expected_1_image_tag, $tag_one_image_by_params);

        $tag_one_image_by_breakpoints = cl_image_tag(
            self::$public_id,
            array_merge(
                self::$common_image_options,
                array('srcset' => array('breakpoints' => array(self::$last_breakpoint)))
            )
        );
        $this->assertEquals($expected_1_image_tag, $tag_one_image_by_breakpoints);

        // Should support custom transformation for srcset items
        $custom_transformation = array("transformation" => array("crop" => "crop", "width" => 10, "height" => 20));

        $tag_custom_transformation = cl_image_tag(
            self::$public_id,
            array_merge(
                self::$common_image_options,
                array('srcset' => array_merge(
                    self::$common_srcset,
                    $custom_transformation
                ))
            )
        );

        $custom_transformation_str = 'c_crop,h_20,w_10';
        $custom_expected_tag = self::get_expected_cl_image_tag(
            self::$public_id,
            self::$common_transformation_str,
            $custom_transformation_str,
            self::$breakpoints_arr
        );

        $this->assertEquals($custom_expected_tag, $tag_custom_transformation);

        // Should populate sizes attribute
        $tag_with_sizes = cl_image_tag(
            self::$public_id,
            array_merge(
                self::$common_image_options,
                array('srcset' => array_merge(
                    self::$common_srcset,
                    array('sizes' => true)
                ))
            )
        );

        $expected_sizes_attr = '(max-width: 100px) 100px, (max-width: 200px) 200px, ' .
                               '(max-width: 300px) 300px, (max-width: 399px) 399px';
        $expected_tag_with_sizes = self::get_expected_cl_image_tag(
            self::$public_id,
            self::$common_transformation_str,
            '',
            self::$breakpoints_arr,
            array('sizes' => $expected_sizes_attr)
        );
        $this->assertEquals($expected_tag_with_sizes, $tag_with_sizes);

        // Should support srcset string value
        $raw_srcset_value = "some srcset data as is";
        $tag_with_raw_srcset = cl_image_tag(
            self::$public_id,
            array_merge(
                self::$common_image_options,
                array('srcset' => $raw_srcset_value)
            )
        );

        $expected_raw_srcset = self::get_expected_cl_image_tag(
            self::$public_id,
            self::$common_transformation_str,
            '',
            array(),
            array('srcset' => $raw_srcset_value)
        );

        $this->assertEquals($expected_raw_srcset, $tag_with_raw_srcset);

        // Should remove width and height attributes in case srcset is specified, but passed to transformation
        $tag_with_sizes = cl_image_tag(
            self::$public_id,
            array_merge(
                array_merge(
                    self::$common_image_options,
                    array('width' => 500, 'height' => 500)
                ),
                array('srcset' => self::$common_srcset)
            )
        );

        $expected_tag_without_width_and_height = self::get_expected_cl_image_tag(
            self::$public_id,
            'e_sepia,h_500,w_500',
            '',
            self::$breakpoints_arr
        );
        $this->assertEquals($expected_tag_without_width_and_height, $tag_with_sizes);

        # Should throw InvalidArgumentException on invalid values
        $invalid_breakpoints = array(
            array('sizes' => true),                                             // srcset data not provided
            array('max_width' => 300, 'max_images' => 3),                       // no min_width
            array('min_width' => '1', 'max_width' => 300, 'max_images' => 3),   // invalid min_width
            array('min_width' => 100, 'max_images' => 3),                       // no max_width
            array('min_width' => '1', 'max_width' => '3', 'max_images' => 3),   // invalid max_width
            array('min_width' => 200, 'max_width' => 100, 'max_images' => 3),   // min_width > max_width
            array('min_width' => 100, 'max_width' => 300),                      // no max_images
            array('min_width' => 100, 'max_width' => 300, 'max_images' => 0),   // invalid max_images
            array('min_width' => 100, 'max_width' => 300, 'max_images' => -17), // invalid max_images
            array('min_width' => 100, 'max_width' => 300, 'max_images' => '3'), // invalid max_images
            array('min_width' => 100, 'max_width' => 300, 'max_images' => null), // invalid max_images
        );

        foreach ($invalid_breakpoints as $value) {
            try {
                cl_image_tag(self::$public_id, array_merge(self::$common_image_options, array('srcset' => $value)));

                $this->fail('InvalidArgumentException was not thrown');
            } catch (\InvalidArgumentException $e) {
            }
        }
    }

    public function test_create_a_tag_with_custom_attributes_legacy_approach()
    {
        $tag_with_custom_legacy_attribute = cl_image_tag(
            self::$public_id,
            array_merge(
                self::$common_image_options,
                self::$custom_attributes
            )
        );

        $expected_custom_attributes_tag = self::get_expected_cl_image_tag(
            self::$public_id,
            self::$common_transformation_str,
            '',
            array(),
            self::$custom_attributes
        );

        $this->assertEquals($expected_custom_attributes_tag, $tag_with_custom_legacy_attribute);
    }

    public function test_consume_custom_attributes_from_attributes_key()
    {
        $tag_with_custom_attribute = cl_image_tag(
            self::$public_id,
            array_merge(
                self::$common_image_options,
                array('attributes' => self::$custom_attributes)
            )
        );
        $expected_custom_attributes_tag = self::get_expected_cl_image_tag(
            self::$public_id,
            self::$common_transformation_str,
            '',
            array(),
            self::$custom_attributes
        );
        $this->assertEquals($expected_custom_attributes_tag, $tag_with_custom_attribute);
    }

    public function test_override_existing_attributes_with_specified_by_custom_ones()
    {
        $updated_attributes = array('alt' => 'updated alt');
        $tag_with_custom_overriden_attribute = cl_image_tag(
            self::$public_id,
            array_merge(
                self::$common_image_options,
                array('alt' => 'original alt', 'attributes' => $updated_attributes)
            )
        );

        $expected_overriden_attributes_tag = self::get_expected_cl_image_tag(
            self::$public_id,
            self::$common_transformation_str,
            '',
            array(),
            $updated_attributes
        );
        $this->assertEquals($expected_overriden_attributes_tag, $tag_with_custom_overriden_attribute);
    }

    public function test_dpr_auto()
    {
        // should support width=auto
        $tag = cl_image_tag("hello", array("dpr" => "auto", "format" => "png"));
        $this->assertEquals(
            "<img class='cld-hidpi' data-src='" . self::DEFAULT_UPLOAD_PATH . "dpr_auto/hello.png'/>",
            $tag
        );
    }

    public function test_cl_sprite_tag()
    {
        $url = cl_sprite_tag("mytag", array("crop" => "fill", "width" => 10, "height" => 10));
        $this->assertEquals(
            "<link rel='stylesheet' type='text/css' href='http://res.cloudinary.com/test123/image/sprite/c_fill,h_10,w_10/mytag.css'>",
            $url
        );
    }

    public function test_cl_video_thumbnail_path()
    {
        $this->assertEquals(cl_video_thumbnail_path('movie_id'), TagTest::VIDEO_UPLOAD_PATH . "movie_id.jpg");
        $this->assertEquals(
            cl_video_thumbnail_path('movie_id', array('width' => 100)),
            TagTest::VIDEO_UPLOAD_PATH . "w_100/movie_id.jpg"
        );
    }

    public function test_cl_video_thumbnail_tag()
    {
        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "movie_id.jpg";
        $this->assertEquals(
            cl_video_thumbnail_tag('movie_id'),
            "<img src='$expected_url' />"
        );

        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "w_100/movie_id.jpg";
        $this->assertEquals(
            cl_video_thumbnail_tag('movie_id', array('width' => 100)),
            "<img src='$expected_url' width='100'/>"
        );
    }

    public function test_cl_video_tag()
    {
        //default
        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "movie";
        $this->assertEquals(
            cl_video_tag('movie'),
            "<video poster='$expected_url.jpg'>" .
            "<source src='$expected_url.webm' type='video/webm'>" .
            "<source src='$expected_url.mp4' type='video/mp4'>" .
            "<source src='$expected_url.ogv' type='video/ogg'>" .
            "</video>"
        );
    }

    public function test_cl_video_tag_with_attributes()
    {
        //test video attributes
        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "movie";
        $this->assertEquals(
            cl_video_tag(
                'movie',
                array('autoplay' => true, 'controls', 'loop', 'muted' => "true", 'preload', 'style' => 'border: 1px')
            ),
            "<video autoplay='1' controls loop muted='true' poster='$expected_url.jpg' preload style='border: 1px'>" .
            "<source src='$expected_url.webm' type='video/webm'>" .
            "<source src='$expected_url.mp4' type='video/mp4'>" .
            "<source src='$expected_url.ogv' type='video/ogg'>" .
            "</video>"
        );
    }

    public function test_cl_video_tag_with_transformation()
    {
        //test video attributes
        $options = array(
            'source_types' => "mp4",
            'html_height' => "100",
            'html_width' => "200",
            'video_codec' => array('codec' => 'h264'),
            'audio_codec' => 'acc',
            'start_offset' => 3,
        );
        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "ac_acc,so_3,vc_h264/movie";
        $this->assertEquals(
            cl_video_tag('movie', $options),
            "<video height='100' poster='$expected_url.jpg' src='$expected_url.mp4' width='200'></video>"
        );

        unset($options['source_types']);
        $this->assertEquals(
            cl_video_tag('movie', $options),
            "<video height='100' poster='$expected_url.jpg' width='200'>" .
            "<source src='$expected_url.webm' type='video/webm'>" .
            "<source src='$expected_url.mp4' type='video/mp4'>" .
            "<source src='$expected_url.ogv' type='video/ogg'>" .
            "</video>"
        );

        unset($options['html_height']);
        unset($options['html_width']);
        $options['width'] = 250;
        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "ac_acc,so_3,vc_h264,w_250/movie";
        $this->assertEquals(
            cl_video_tag('movie', $options),
            "<video poster='$expected_url.jpg' width='250'>" .
            "<source src='$expected_url.webm' type='video/webm'>" .
            "<source src='$expected_url.mp4' type='video/mp4'>" .
            "<source src='$expected_url.ogv' type='video/ogg'>" .
            "</video>"
        );

        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "ac_acc,c_fit,so_3,vc_h264,w_250/movie";
        $options['crop'] = 'fit';
        $this->assertEquals(
            cl_video_tag('movie', $options),
            "<video poster='$expected_url.jpg'>" .
            "<source src='$expected_url.webm' type='video/webm'>" .
            "<source src='$expected_url.mp4' type='video/mp4'>" .
            "<source src='$expected_url.ogv' type='video/ogg'>" .
            "</video>"
        );
    }

    public function test_cl_video_tag_with_fallback()
    {
        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "movie";
        $fallback = "<span id='spanid'>Cannot display video</span>";
        $this->assertEquals(
            cl_video_tag('movie', array('fallback_content' => $fallback)),
            "<video poster='$expected_url.jpg'>" .
            "<source src='$expected_url.webm' type='video/webm'>" .
            "<source src='$expected_url.mp4' type='video/mp4'>" .
            "<source src='$expected_url.ogv' type='video/ogg'>" .
            $fallback .
            "</video>"
        );
        $this->assertEquals(
            cl_video_tag('movie', array('fallback_content' => $fallback, 'source_types' => "mp4")),
            "<video poster='$expected_url.jpg' src='$expected_url.mp4'>" . $fallback . "</video>"
        );
    }

    public function test_cl_video_tag_with_source_types()
    {
        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "movie";
        $this->assertEquals(
            cl_video_tag('movie', array('source_types' => array('ogv', 'mp4'))),
            "<video poster='$expected_url.jpg'>" .
            "<source src='$expected_url.ogv' type='video/ogg'>" .
            "<source src='$expected_url.mp4' type='video/mp4'>" .
            "</video>"
        );
    }

    public function test_cl_video_tag_with_source_transformation()
    {
        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "q_50/w_100/movie";
        $expected_ogv_url = TagTest::VIDEO_UPLOAD_PATH . "q_50/q_70,w_100/movie";
        $expected_mp4_url = TagTest::VIDEO_UPLOAD_PATH . "q_50/q_30,w_100/movie";
        $this->assertEquals(
            cl_video_tag(
                'movie',
                array(
                    'width' => 100,
                    'transformation' => array(array('quality' => 50)),
                    'source_transformation' => array(
                        'ogv' => array('quality' => 70),
                        'mp4' => array('quality' => 30),
                    ),
                )
            ),
            "<video poster='$expected_url.jpg' width='100'>" .
            "<source src='$expected_url.webm' type='video/webm'>" .
            "<source src='$expected_mp4_url.mp4' type='video/mp4'>" .
            "<source src='$expected_ogv_url.ogv' type='video/ogg'>" .
            "</video>"
        );

        $this->assertEquals(
            cl_video_tag(
                'movie',
                array(
                    'width' => 100,
                    'transformation' => array(array('quality' => 50)),
                    'source_transformation' => array(
                        'ogv' => array('quality' => 70),
                        'mp4' => array('quality' => 30),
                    ),
                    'source_types' => array('webm', 'mp4'),
                )
            ),
            "<video poster='$expected_url.jpg' width='100'>" .
            "<source src='$expected_url.webm' type='video/webm'>" .
            "<source src='$expected_mp4_url.mp4' type='video/mp4'>" .
            "</video>"
        );
    }

    public function test_cl_video_tag_with_poster()
    {
        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "movie";

        $expected_poster_url = 'http://image/somewhere.jpg';
        $this->assertEquals(
            cl_video_tag('movie', array('poster' => $expected_poster_url, 'source_types' => "mp4")),
            "<video poster='$expected_poster_url' src='$expected_url.mp4'></video>"
        );

        $expected_poster_url = TagTest::VIDEO_UPLOAD_PATH . "g_north/movie.jpg";
        $this->assertEquals(
            cl_video_tag(
                'movie',
                array('poster' => array('gravity' => 'north'), 'source_types' => "mp4")
            ),
            "<video poster='$expected_poster_url' src='$expected_url.mp4'></video>"
        );

        $expected_poster_url = TagTest::DEFAULT_UPLOAD_PATH . "g_north/my_poster.jpg";
        $this->assertEquals(
            cl_video_tag(
                'movie',
                array(
                    'poster' => array('gravity' => 'north', 'public_id' => 'my_poster', 'format' => 'jpg'),
                    'source_types' => "mp4",
                )
            ),
            "<video poster='$expected_poster_url' src='$expected_url.mp4'></video>"
        );

        $this->assertEquals(
            cl_video_tag('movie', array('poster' => null, 'source_types' => "mp4")),
            "<video src='$expected_url.mp4'></video>"
        );

        $this->assertEquals(
            cl_video_tag('movie', array('poster' => false, 'source_types' => "mp4")),
            "<video src='$expected_url.mp4'></video>"
        );
    }

    /**
     * Check that cl_video_tag encodes special characters.
     */
    public function test_cl_video_tag_special_characters_encoding()
    {
        $expected_url = TagTest::VIDEO_UPLOAD_PATH . "movie%27s%20id%21%40%23%24%25%5E%26%2A%28";

        $this->assertEquals(
            "<video poster='$expected_url.jpg' src='$expected_url.mp4'></video>",
            cl_video_tag("movie's id!@#$%^&*(", array('source_types' => "mp4"))
        );
    }


    public function test_upload_tag()
    {
        $pattern = "/<input class='cloudinary-fileupload' " .
            "data-cloudinary-field='image' " .
            "data-form-data='{\&quot;timestamp\&quot;:\d+,\&quot;signature\&quot;:\&quot;\w+\&quot;," .
            "\&quot;api_key\&quot;:\&quot;a\&quot;}' " .
            "data-url='http[^']+\/v1_1\/test123\/auto\/upload' " .
            "name='file' type='file'\/>/";
        $this->assertRegExp($pattern, cl_upload_tag('image'));
        $this->assertRegExp($pattern, cl_image_upload_tag('image'));

        $pattern = "/<input class='cloudinary-fileupload' " .
            "data-cloudinary-field='image' " .
            "data-form-data='{\&quot;timestamp\&quot;:\d+,\&quot;signature\&quot;:\&quot;\w+\&quot;," .
            "\&quot;api_key\&quot;:\&quot;a\&quot;}' " .
            "data-max-chunk-size='5000000' " .
            "data-url='http[^']+\/v1_1\/test123\/auto\/upload_chunked' " .
            "name='file' type='file'\/>/";
        $this->assertRegExp(
            $pattern,
            cl_upload_tag('image', array('chunk_size' => 5000000))
        );

        $pattern = "/<input class='classy cloudinary-fileupload' " .
            "data-cloudinary-field='image' " .
            "data-form-data='{\&quot;timestamp\&quot;:\d+,\&quot;signature\&quot;:\&quot;\w+\&quot;," .
            "\&quot;api_key\&quot;:\&quot;a\&quot;}' " .
            "data-url='http[^']+\/v1_1\/test123\/auto\/upload' " .
            "name='file' type='file'\/>/";
        $this->assertRegExp(
            $pattern,
            cl_upload_tag('image', array("html" => array('class' => 'classy')))
        );
    }

    private function cloudinary_url_assertion($source, $options, $expected, $expected_options = array())
    {
        $url = Cloudinary::cloudinary_url($source, $options);
        $this->assertEquals($expected_options, $options);
        $this->assertEquals($expected, $url);
    }

}
