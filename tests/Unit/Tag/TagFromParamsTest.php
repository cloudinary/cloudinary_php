<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Tag;

use Cloudinary\ArrayUtils;
use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\Media;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Tag\ImageTag;
use Cloudinary\Tag\SpriteTag;
use Cloudinary\Tag\Tag;
use Cloudinary\Tag\UploadTag;
use Cloudinary\Tag\VideoTag;
use Cloudinary\Tag\VideoThumbnailTag;
use DOMDocument;

/**
 * Class TagFromParamsTest
 */
final class TagFromParamsTest extends ImageTagTestCase
{
    const API_KEY    = 'a';
    const API_SECRET = 'b';

    const DEFAULT_PATH        = 'http://res.cloudinary.com/test123';
    const DEFAULT_UPLOAD_PATH = 'http://res.cloudinary.com/test123/image/upload/';
    const VIDEO_PATH   = 'http://res.cloudinary.com/test123/video/';
    const VIDEO_UPLOAD_PATH   = self::VIDEO_PATH . 'upload/';
    const VIDEO_FETCH_PATH   = self::VIDEO_PATH . 'fetch/';

    private static $publicId                = 'sample.jpg';
    private static $commonTransformationStr = 'e_sepia';
    private static $minWidth                = 375;
    private static $maxWidth                = 3840;
    private static $maxImages;
    private static $customAttributes        = ['custom_attr1' => 'custom_value1', 'custom_attr2' => 'custom_value2'];
    private static $commonImageOptions      = [
        'effect'       => 'sepia',
        'cloud_name'   => 'test123',
        'client_hints' => false,
    ];
    private static $commonSrcset;
    private static $breakpointsArr;
    private static $sizesAttr = '100vw';

    public static function setUpBeforeClass()
    {
        self::$breakpointsArr = [828, 1366, 1536, 1920, 3840];
        self::$maxImages      = count(self::$breakpointsArr);
        self::$commonSrcset   = ['breakpoints' => self::$breakpointsArr];
    }


    public function testImageTag()
    {
        $tag = ImageTag::fromParams('test', ['width' => 10, 'height' => 10, 'crop' => 'fill', 'format' => 'png']);
        self::assertStrEquals(
            "<img src='" . self::DEFAULT_UPLOAD_PATH . "c_fill,h_10,w_10/test.png' height='10' width='10'/>",
            $tag
        );
    }

    /**
     * Should create a meta tag with client hints
     */
    public function testClientHintsMetaTag()
    {
        $doc = new DOMDocument();
        $doc->loadHTML(Tag::clientHintsMetaTag());
        $tags = $doc->getElementsByTagName('meta');
        self::assertEquals(1, $tags->length);
        self::assertStrEquals('DPR, Viewport-Width, Width', $tags->item(0)->getAttribute('content'));
        self::assertStrEquals('Accept-CH', $tags->item(0)->getAttribute('http-equiv'));
    }

    /**
     * Check that ImageTag encodes special characters.
     */
    public function testImageTagSpecialCharactersEncoding()
    {
        $tag      = ImageTag::fromParams(
            "test's special < \"characters\" >",
            ['width' => 10, 'height' => 10, 'crop' => 'fill', 'format' => 'png', 'alt' => "< test's > special \""]
        );
        $expected = "<img src='" . self::DEFAULT_UPLOAD_PATH . 'c_fill,h_10,w_10/' .
                    "test%27s%20special%20%3C%20%22characters%22%20%3E.png'" .
                    " alt='&lt; test&#039;s &gt; special &quot;' height='10' width='10'/>";

        self::assertStrEquals($expected, $tag);
    }

    public function testResponsiveWidth()
    {
        // should add responsive width transformation
        $tag = ImageTag::fromParams('hello', ['responsive_width' => true, 'format' => 'png']);
        self::assertStrEquals(
            "<img class='cld-responsive' data-src='" . self::DEFAULT_UPLOAD_PATH . "c_limit,w_auto/hello.png'/>",
            $tag
        );

        $options = ['width' => 100, 'height' => 100, 'crop' => 'crop', 'responsive_width' => true];
        $result  = Media::fromParams('test', $options);
        self::assertStrEquals(self::DEFAULT_UPLOAD_PATH . 'c_crop,h_100,w_100/c_limit,w_auto/test', $result);
        Configuration::instance()->importJson(
            [
                'responsive_width_transformation' => [
                    'width' => 'auto:breakpoints',
                    'crop'  => 'pad',
                ],
            ]
        );
        $options = ['width' => 100, 'height' => 100, 'crop' => 'crop', 'responsive_width' => true];
        $result  = Media::fromParams('test', $options);
        self::assertStrEquals(
            self::DEFAULT_UPLOAD_PATH . 'c_crop,h_100,w_100/c_pad,w_auto:breakpoints/test',
            $result
        );
    }

    public function testWidthAuto()
    {
        // should support width=auto
        $tag = ImageTag::fromParams('hello', ['width' => 'auto', 'crop' => 'limit', 'format' => 'png']);
        self::assertStrEquals(
            "<img class='cld-responsive' data-src='" . self::DEFAULT_UPLOAD_PATH . "c_limit,w_auto/hello.png'/>",
            $tag
        );
        $tag = ImageTag::fromParams('hello', ['width' => 'auto:breakpoints', 'crop' => 'limit', 'format' => 'png']);
        self::assertStrEquals(
            "<img class='cld-responsive' data-src='" .
            self::DEFAULT_UPLOAD_PATH . "c_limit,w_auto:breakpoints/hello.png'/>",
            $tag
        );
        self::assertCloudinaryUrl(
            'test',
            ['width' => 'auto:20', 'crop' => 'fill'],
            self::DEFAULT_UPLOAD_PATH . 'c_fill,w_auto:20/test'
        );
        self::assertCloudinaryUrl(
            'test',
            ['width' => 'auto:20:350', 'crop' => 'fill'],
            self::DEFAULT_UPLOAD_PATH . 'c_fill,w_auto:20:350/test'
        );
        self::assertCloudinaryUrl(
            'test',
            ['width' => 'auto:breakpoints', 'crop' => 'fill'],
            self::DEFAULT_UPLOAD_PATH . 'c_fill,w_auto:breakpoints/test'
        );
        self::assertCloudinaryUrl(
            'test',
            ['width' => 'auto:breakpoints_100_1900_20_15', 'crop' => 'fill'],
            self::DEFAULT_UPLOAD_PATH . 'c_fill,w_auto:breakpoints_100_1900_20_15/test'
        );
        self::assertCloudinaryUrl(
            'test',
            ['width' => 'auto:breakpoints:json', 'crop' => 'fill'],
            self::DEFAULT_UPLOAD_PATH . 'c_fill,w_auto:breakpoints:json/test'
        );
    }

    /**
     * @param        $options
     * @param string $message
     */
    public function sharedClientHints($options, $message = '')
    {
        $tag = ImageTag::fromParams('sample.jpg', $options);
        self::assertStrEquals(
            "<img src='http://res.cloudinary.com/test/image/upload/c_scale,dpr_auto,w_auto/sample.jpg'/>",
            $tag,
            $message
        );
        $tag = ImageTag::fromParams('sample.jpg', array_merge(['responsive' => true], $options));
        self::assertStrEquals(
            "<img src='http://res.cloudinary.com/test/image/upload/c_scale,dpr_auto,w_auto/sample.jpg'/>",
            $tag,
            $message
        );
    }

    public function testClientHintsAsOption()
    {
        $this->sharedClientHints(
            [
                'dpr'          => 'auto',
                'cloud_name'   => 'test',
                'width'        => 'auto',
                'crop'         => 'scale',
                'client_hints' => true,
            ],
            'support client_hints as an option'
        );
    }

    public function testClientHintsAsGlobal()
    {
        Configuration::instance()->importJson(['client_hints' => true]);
        $this->sharedClientHints(
            [
                'dpr'        => 'auto',
                'cloud_name' => 'test',
                'width'      => 'auto',
                'crop'       => 'scale',
            ],
            'support client hints as global configuration'
        );
    }

    public function testClientHintsFalse()
    {
        Configuration::instance()->importJson(['responsive' => true]);
        $tag = ImageTag::fromParams(
            'sample.jpg',
            [
                'width'        => 'auto',
                'crop'         => 'scale',
                'cloud_name'   => 'test123',
                'client_hints' => false,
            ]
        );
        self::assertStrEquals(
            "<img class='cld-responsive' data-src='" . self::DEFAULT_UPLOAD_PATH . "c_scale,w_auto/sample.jpg'/>",
            $tag,
            'should use normal responsive behaviour'
        );
    }

    /**
     * Should create srcset attribute with provided breakpoints
     */
    public function testImageTagSrcset()
    {
        $expectedTag = self::expectedImageTagFromParams(
            self::$publicId,
            self::$commonTransformationStr,
            '',
            self::$breakpointsArr
        );

        $tagWithBreakpoints = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                self::$commonImageOptions,
                ['srcset' => self::$commonSrcset]
            )
        );

        self::assertStrEquals(
            $expectedTag,
            $tagWithBreakpoints,
            'Should create img srcset attribute with provided breakpoints'
        );
    }

    public function testSupportSrcsetAttributeDefinedByMinWidthMaxWidthAndMaxImages()
    {
        $tagMinMaxCount = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                self::$commonImageOptions,
                [
                    'srcset' => [
                        'min_width'  => self::$minWidth,
                        'max_width'  => self::$maxWidth,
                        'max_images' => count(self::$breakpointsArr),
                        'auto_optimal_breakpoints' => true
                    ],
                ]
            )
        );

        $expectedTag = self::expectedImageTagFromParams(
            self::$publicId,
            self::$commonTransformationStr,
            '',
            self::$breakpointsArr
        );

        self::assertStrEquals(
            $expectedTag,
            $tagMinMaxCount,
            'Should support srcset attribute defined by min_width, max_width, and max_images'
        );

        // Should support 1 image in srcset
        $tagOneImageByParams = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                self::$commonImageOptions,
                [
                    'srcset' => [
                        'min_width'  => self::$breakpointsArr[0],
                        'max_width'  => self::$maxWidth,
                        'max_images' => 1,
                        'auto_optimal_breakpoints' => true
                    ],
                ]
            )
        );

        $expected1ImageTag = self::expectedImageTagFromParams(
            self::$publicId,
            self::$commonTransformationStr,
            '',
            [self::$maxWidth]
        );

        self::assertStrEquals($expected1ImageTag, $tagOneImageByParams);

        $tagOneImageByBreakpoints = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                self::$commonImageOptions,
                ['srcset' => ['breakpoints' => [self::$maxWidth]]]
            )
        );
        self::assertStrEquals($expected1ImageTag, $tagOneImageByBreakpoints);

        // Should populate sizes attribute
        $tagWithSizes = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                self::$commonImageOptions,
                [
                    'srcset' => array_merge(
                        self::$commonSrcset,
                        ['sizes' => true]
                    ),
                ]
            )
        );

        $expectedTagWithSizes = self::expectedImageTagFromParams(
            self::$publicId,
            self::$commonTransformationStr,
            '',
            self::$breakpointsArr,
            ['sizes' => self::$sizesAttr]
        );
        self::assertStrEquals($expectedTagWithSizes, $tagWithSizes);

        // Should support srcset string value
        $rawSrcsetValue   = 'some srcset data as is';
        $tagWithRawSrcset = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                self::$commonImageOptions,
                ['attributes' => ['srcset' => $rawSrcsetValue]]
            )
        );

        $expectedRawSrcset = self::expectedImageTagFromParams(
            self::$publicId,
            self::$commonTransformationStr,
            '',
            [],
            ['srcset' => $rawSrcsetValue]
        );

        self::assertStrEquals($expectedRawSrcset, $tagWithRawSrcset);

        // Should remove width and height attributes in case srcset is specified, but passed to transformation
        $tagWithSizes = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                array_merge(
                    self::$commonImageOptions,
                    ['width' => 500, 'height' => 500]
                ),
                ['srcset' => self::$commonSrcset]
            )
        );

        $expectedTagWithoutWidthAndHeight = self::expectedImageTagFromParams(
            self::$publicId,
            'e_sepia,h_500,w_500',
            '',
            self::$breakpointsArr
        );
        self::assertStrEquals($expectedTagWithoutWidthAndHeight, $tagWithSizes);
    }

    public function testCreateATagWithCustomAttributesLegacyApproach()
    {
        $tagWithCustomLegacyAttribute = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                self::$commonImageOptions,
                self::$customAttributes
            )
        );

        $expectedCustomAttributesTag = self::expectedImageTagFromParams(
            self::$publicId,
            self::$commonTransformationStr,
            '',
            [],
            self::$customAttributes
        );

        self::assertStrEquals($expectedCustomAttributesTag, $tagWithCustomLegacyAttribute);
    }

    public function testCreateATagWithLegacySrcsetAttribute()
    {
        $srcsetAttribute              = ['srcset' => 'http://custom.srcset.attr/sample.jpg 100w'];
        $tagWithCustomSrcsetAttribute = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                self::$commonImageOptions,
                $srcsetAttribute
            )
        );

        $expectedCustomAttributesTag = self::expectedImageTagFromParams(
            self::$publicId,
            self::$commonTransformationStr,
            '',
            [],
            $srcsetAttribute
        );

        self::assertStrEquals($expectedCustomAttributesTag, $tagWithCustomSrcsetAttribute);
    }


    public function testConsumeCustomAttributesFromAttributesKey()
    {
        $tagWithCustomAttribute      = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                self::$commonImageOptions,
                ['attributes' => self::$customAttributes]
            )
        );
        $expectedCustomAttributesTag = self::expectedImageTagFromParams(
            self::$publicId,
            self::$commonTransformationStr,
            '',
            [],
            self::$customAttributes
        );
        self::assertStrEquals($expectedCustomAttributesTag, $tagWithCustomAttribute);
    }

    public function testOverrideExistingAttributesWithSpecifiedByCustomOnes()
    {
        $updatedAttributes               = ['alt' => 'updated alt'];
        $tagWithCustomOverridenAttribute = ImageTag::fromParams(
            self::$publicId,
            array_merge(
                self::$commonImageOptions,
                ['alt' => 'original alt', 'attributes' => $updatedAttributes]
            )
        );

        $expectedOverridenAttributesTag = self::expectedImageTagFromParams(
            self::$publicId,
            self::$commonTransformationStr,
            '',
            [],
            $updatedAttributes
        );
        self::assertStrEquals($expectedOverridenAttributesTag, $tagWithCustomOverridenAttribute);
    }

    public function testDprAuto()
    {
        // should support width=auto
        $tag = ImageTag::fromParams('hello', ['dpr' => 'auto', 'format' => 'png']);
        self::assertStrEquals(
            "<img class='cld-hidpi' data-src='" . self::DEFAULT_UPLOAD_PATH . "dpr_auto/hello.png'/>",
            $tag
        );
    }

    public function testSpriteTag()
    {
        self::assertStrEquals(
            "<link href='" . self::DEFAULT_PATH . "/image/sprite/c_fill,h_10,w_10/mytag.css' " .
            "rel='stylesheet' type='text/css'/>",
            SpriteTag::fromParams('mytag', ['crop' => 'fill', 'width' => 10, 'height' => 10])
        );
    }

    public function testVideoThumbnailTag()
    {
        $expectedUrl = self::VIDEO_UPLOAD_PATH . 'movie_id.jpg';
        self::assertStrEquals(
            "<img src='$expectedUrl'/>",
            VideoThumbnailTag::fromParams('movie_id')
        );

        $expectedUrl = self::VIDEO_UPLOAD_PATH . 'w_100/movie_id.jpg';
        self::assertStrEquals(
            "<img src='$expectedUrl' width='100'/>",
            VideoThumbnailTag::fromParams('movie_id', ['width' => 100])
        );
    }

    public function testVideoTag()
    {
        //default
        $expectedUrl = self::VIDEO_UPLOAD_PATH . 'movie';
        self::assertStrEquals(
            "<video poster='$expectedUrl.jpg'>" .
            "<source src='$expectedUrl.webm' type='video/webm'>" .
            "<source src='$expectedUrl.mp4' type='video/mp4'>" .
            "<source src='$expectedUrl.ogv' type='video/ogg'>" .
            '</video>',
            VideoTag::fromParams('movie')
        );
    }

    public function testVideoTagFetch()
    {
        $videoUrl = self::FETCH_VIDEO_URL;
        $prefixUrl = self::VIDEO_FETCH_PATH;

        self::assertStrEquals(
            "<video poster='{$prefixUrl}f_jpg/$videoUrl'>" .
            "<source src='{$prefixUrl}f_webm/$videoUrl' type='video/webm'>" .
            "<source src='{$prefixUrl}f_mp4/$videoUrl' type='video/mp4'>" .
            "<source src='{$prefixUrl}f_ogv/$videoUrl' type='video/ogg'>" .
            '</video>',
            VideoTag::fromParams($videoUrl, [DeliveryType::KEY => DeliveryType::FETCH])
        );
    }

    public function testVideoTagUseFetchFormat()
    {
        $videoId = self::VIDEO_NAME;
        $prefixUrl = self::VIDEO_UPLOAD_PATH;

        Configuration::instance()->importJson(['use_fetch_format' => true]);

        self::assertStrEquals(
            "<video poster='{$prefixUrl}f_jpg/$videoId'>" .
            "<source src='{$prefixUrl}f_webm/$videoId' type='video/webm'>" .
            "<source src='{$prefixUrl}f_mp4/$videoId' type='video/mp4'>" .
            "<source src='{$prefixUrl}f_ogv/$videoId' type='video/ogg'>" .
            '</video>',
            VideoTag::fromParams($videoId)
        );
    }

    public function testVideoTagWithAttributes()
    {
        //test video attributes
        $expectedUrl = self::VIDEO_UPLOAD_PATH . 'movie';
        self::assertStrEquals(
            "<video autoplay controls loop muted='true' poster='$expectedUrl.jpg' preload style='border: 1px'>" .
            "<source src='$expectedUrl.webm' type='video/webm'>" .
            "<source src='$expectedUrl.mp4' type='video/mp4'>" .
            "<source src='$expectedUrl.ogv' type='video/ogg'>" .
            '</video>',
            VideoTag::fromParams(
                'movie',
                ['autoplay' => true, 'controls', 'loop', 'muted' => 'true', 'preload', 'style' => 'border: 1px']
            )
        );
    }

    public function testVideoTagWithTransformation()
    {
        //test video attributes
        $options     = [
            'source_types' => 'mp4',
            'html_height'  => '100',
            'html_width'   => '200',
            'video_codec'  => ['codec' => 'h264'],
            'audio_codec'  => 'acc',
            'start_offset' => 3,
        ];
        $expectedUrl = self::VIDEO_UPLOAD_PATH . 'ac_acc,so_3,vc_h264/movie';
        self::assertStrEquals(
            "<video height='100' poster='$expectedUrl.jpg' src='$expectedUrl.mp4' width='200'></video>",
            VideoTag::fromParams('movie', $options)
        );

        unset($options['source_types']);
        self::assertStrEquals(
            "<video height='100' poster='$expectedUrl.jpg' width='200'>" .
            "<source src='$expectedUrl.webm' type='video/webm'>" .
            "<source src='$expectedUrl.mp4' type='video/mp4'>" .
            "<source src='$expectedUrl.ogv' type='video/ogg'>" .
            '</video>',
            VideoTag::fromParams('movie', $options)
        );

        unset($options['html_height'], $options['html_width']);
        $options['width'] = 250;
        $expectedUrl      = self::VIDEO_UPLOAD_PATH . 'ac_acc,so_3,vc_h264,w_250/movie';
        self::assertStrEquals(
            "<video poster='$expectedUrl.jpg' width='250'>" .
            "<source src='$expectedUrl.webm' type='video/webm'>" .
            "<source src='$expectedUrl.mp4' type='video/mp4'>" .
            "<source src='$expectedUrl.ogv' type='video/ogg'>" .
            '</video>',
            VideoTag::fromParams('movie', $options)
        );

        $expectedUrl     = self::VIDEO_UPLOAD_PATH . 'ac_acc,c_fit,so_3,vc_h264,w_250/movie';
        $options['crop'] = 'fit';
        self::assertStrEquals(
            "<video poster='$expectedUrl.jpg'>" .
            "<source src='$expectedUrl.webm' type='video/webm'>" .
            "<source src='$expectedUrl.mp4' type='video/mp4'>" .
            "<source src='$expectedUrl.ogv' type='video/ogg'>" .
            '</video>',
            VideoTag::fromParams('movie', $options)
        );
    }

    public function testVideoTagWithFallback()
    {
        $expectedUrl = self::VIDEO_UPLOAD_PATH . 'movie';
        $fallback    = "<span id='spanid'>Cannot display video</span>";
        self::assertStrEquals(
            "<video poster='$expectedUrl.jpg'>" .
            "<source src='$expectedUrl.webm' type='video/webm'>" .
            "<source src='$expectedUrl.mp4' type='video/mp4'>" .
            "<source src='$expectedUrl.ogv' type='video/ogg'>" .
            $fallback .
            '</video>',
            VideoTag::fromParams('movie', ['fallback_content' => $fallback])
        );
        self::assertStrEquals(
            "<video poster='$expectedUrl.jpg' src='$expectedUrl.mp4'>" . $fallback . '</video>',
            VideoTag::fromParams('movie', ['fallback_content' => $fallback, 'source_types' => 'mp4'])
        );
    }

    public function testVideoTagWithSourceTypes()
    {
        $expectedUrl = self::VIDEO_UPLOAD_PATH . 'movie';
        self::assertStrEquals(
            "<video poster='$expectedUrl.jpg'>" .
            "<source src='$expectedUrl.ogv' type='video/ogg'>" .
            "<source src='$expectedUrl.mp4' type='video/mp4'>" .
            '</video>',
            VideoTag::fromParams('movie', ['source_types' => ['ogv', 'mp4']])
        );
    }

    public function testVideoTagWithSourceTransformation()
    {
        $expectedUrl      = self::VIDEO_UPLOAD_PATH . 'q_50/w_100/movie';
        $expected_ogv_url = self::VIDEO_UPLOAD_PATH . 'q_50/w_100/q_70/movie';
        $expected_mp4_url = self::VIDEO_UPLOAD_PATH . 'q_50/w_100/q_30/movie';
        self::assertStrEquals(
            "<video poster='$expectedUrl.jpg' width='100'>" .
            "<source src='$expectedUrl.webm' type='video/webm'>" .
            "<source src='$expected_mp4_url.mp4' type='video/mp4'>" .
            "<source src='$expected_ogv_url.ogv' type='video/ogg'>" .
            '</video>',
            VideoTag::fromParams(
                'movie',
                [
                    'width'                 => 100,
                    'transformation'        => [['quality' => 50]],
                    'source_transformation' => [
                        'ogv' => ['quality' => 70],
                        'mp4' => ['quality' => 30],
                    ],
                ]
            )
        );

        self::assertStrEquals(
            "<video poster='$expectedUrl.jpg' width='100'>" .
            "<source src='$expectedUrl.webm' type='video/webm'>" .
            "<source src='$expected_mp4_url.mp4' type='video/mp4'>" .
            '</video>',
            VideoTag::fromParams(
                'movie',
                [
                    'width'                 => 100,
                    'transformation'        => [['quality' => 50]],
                    'source_transformation' => [
                        'ogv' => ['quality' => 70],
                        'mp4' => ['quality' => 30],
                    ],
                    'source_types'          => ['webm', 'mp4'],
                ]
            )
        );
    }

    public function testVideoTagWithPoster()
    {
        $expectedUrl = self::VIDEO_UPLOAD_PATH . 'movie';

        $expectedPosterUrl = 'http://image/somewhere.jpg';
        self::assertStrEquals(
            "<video poster='$expectedPosterUrl' src='$expectedUrl.mp4'></video>",
            VideoTag::fromParams('movie', ['poster' => $expectedPosterUrl, 'source_types' => 'mp4'])
        );

        $expectedPosterUrl = self::VIDEO_UPLOAD_PATH . 'g_north/movie.jpg';
        self::assertStrEquals(
            "<video poster='$expectedPosterUrl' src='$expectedUrl.mp4'></video>",
            VideoTag::fromParams(
                'movie',
                ['poster' => ['gravity' => 'north'], 'source_types' => 'mp4']
            )
        );

        $expectedPosterUrl = self::DEFAULT_UPLOAD_PATH . 'g_north/my_poster.jpg';
        self::assertStrEquals(
            "<video poster='$expectedPosterUrl' src='$expectedUrl.mp4'></video>",
            VideoTag::fromParams(
                'movie',
                [
                    'poster'       => ['gravity' => 'north', 'public_id' => 'my_poster', 'format' => 'jpg'],
                    'source_types' => 'mp4',
                ]
            )
        );

        self::assertStrEquals(
            "<video src='$expectedUrl.mp4'></video>",
            VideoTag::fromParams('movie', ['poster' => null, 'source_types' => 'mp4'])
        );

        self::assertStrEquals(
            "<video src='$expectedUrl.mp4'></video>",
            VideoTag::fromParams('movie', ['poster' => false, 'source_types' => 'mp4'])
        );
    }

    /**
     * Check that VideoTag::fromParams encodes special characters.
     */
    public function testVideoTagSpecialCharactersEncoding()
    {
        $expectedUrl = self::VIDEO_UPLOAD_PATH . 'movie%27s%20id%21%40%23%24%25%5E%26%2A%28';

        self::assertStrEquals(
            "<video poster='$expectedUrl.jpg' src='$expectedUrl.mp4'></video>",
            VideoTag::fromParams("movie's id!@#$%^&*(", ['source_types' => 'mp4'])
        );
    }

    public function testVideoTagDefaultSources()
    {
        $expectedUrl = self::VIDEO_UPLOAD_PATH . '%smovie.%s';

        self::assertStrEquals(
            "<video poster='" . sprintf($expectedUrl, '', 'jpg') . "'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_h265/', 'mp4') . "' type='video/mp4; codecs=hev1'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_vp9/', 'webm') . "' type='video/webm; codecs=vp9'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_auto/', 'mp4') . "' type='video/mp4'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_auto/', 'webm') . "' type='video/webm'>" .
            '</video>',
            VideoTag::fromParams('movie', ['sources' => VideoTag::defaultVideoSources()])
        );
    }

    public function testVideoTagCustomSources()
    {
        $customSources = [
            [
                'type'            => 'mp4',
                'codecs'          => 'vp8, vorbis',
                'transformations' => ['video_codec' => 'auto'],
            ],
            [
                'type'            => 'webm',
                'codecs'          => 'avc1.4D401E, mp4a.40.2',
                'transformations' => ['video_codec' => 'auto'],
            ],
        ];

        $expectedUrl = self::VIDEO_UPLOAD_PATH . '%smovie.%s';

        self::assertStrEquals(
            "<video poster='" . sprintf($expectedUrl, '', 'jpg') . "'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_auto/', 'mp4') .
            "' type='video/mp4; codecs=vp8, vorbis'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_auto/', 'webm') .
            "' type='video/webm; codecs=avc1.4D401E, mp4a.40.2'>" .
            '</video>',
            VideoTag::fromParams('movie', ['sources' => $customSources])
        );
    }

    public function testVideoTagSourcesCodecsArray()
    {
        $customSources = [
            [
                'type'            => 'mp4',
                'codecs'          => ['vp8', 'vorbis'],
                'transformations' => ['video_codec' => 'auto'],
            ],
            [
                'type'            => 'webm',
                'codecs'          => ['avc1.4D401E', 'mp4a.40.2'],
                'transformations' => ['video_codec' => 'auto'],
            ],
        ];
        $expectedUrl   = self::VIDEO_UPLOAD_PATH . '%smovie.%s';

        self::assertStrEquals(
            "<video poster='" . sprintf($expectedUrl, '', 'jpg') . "'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_auto/', 'mp4') .
            "' type='video/mp4; codecs=vp8, vorbis'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_auto/', 'webm') .
            "' type='video/webm; codecs=avc1.4D401E, mp4a.40.2'>" .
            '</video>',
            VideoTag::fromParams('movie', ['sources' => $customSources])
        );
    }

    public function testVideoTagSourcesWithTransformation()
    {
        $options     = [
            'source_types' => 'mp4',
            'html_height'  => '100',
            'html_width'   => '200',
            'audio_codec'  => 'acc',
            'start_offset' => 3,
            'sources'      => VideoTag::defaultVideoSources(),
        ];
        $expectedUrl = self::VIDEO_UPLOAD_PATH . 'ac_acc,so_3/%smovie.%s';

        self::assertStrEquals(
            "<video height='100' poster='" . sprintf($expectedUrl, '', 'jpg') . "' width='200'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_h265/', 'mp4') . "' type='video/mp4; codecs=hev1'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_vp9/', 'webm') . "' type='video/webm; codecs=vp9'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_auto/', 'mp4') . "' type='video/mp4'>" .
            "<source src='" . sprintf($expectedUrl, 'vc_auto/', 'webm') . "' type='video/webm'>" .
            '</video>',
            VideoTag::fromParams('movie', $options)
        );
    }

    public function testUploadTag()
    {
        $pattern = "/<input class='cloudinary-fileupload' " .
                   "data-cloudinary-field='image' " .
                   "data-form-data='{\&quot;timestamp\&quot;:\d+,\&quot;signature\&quot;:\&quot;\w+\&quot;," .
                   "\&quot;api_key\&quot;:\&quot;a\&quot;}' " .
                   "data-max-chunk-size='\d+' " .
                   "data-url='http[^']+\/v1_1\/test123\/auto\/upload' " .
                   "name='file' type='file'\/>/";

        self::assertMatchesRegularExpression($pattern, (string)UploadTag::fromParams('image'));

        $pattern = "/<input class='cloudinary-fileupload' " .
                   "data-cloudinary-field='image' " .
                   "data-form-data='{\&quot;timestamp\&quot;:\d+,\&quot;signature\&quot;:\&quot;\w+\&quot;," .
                   "\&quot;api_key\&quot;:\&quot;a\&quot;}' " .
                   "data-max-chunk-size='5000000' " .
                   "data-url='http[^']+\/v1_1\/test123\/auto\/upload' " .
                   "name='file' type='file'\/>/";
        self::assertMatchesRegularExpression($pattern, (string)UploadTag::fromParams('image', ['chunk_size' => 5000000]));

        $pattern = "/<input class='cloudinary-fileupload classy' " .
                   "data-cloudinary-field='image' " .
                   "data-form-data='{\&quot;timestamp\&quot;:\d+,\&quot;signature\&quot;:\&quot;\w+\&quot;," .
                   "\&quot;api_key\&quot;:\&quot;a\&quot;}' " .
                   "data-max-chunk-size='\d+' " .
                   "data-url='http[^']+\/v1_1\/test123\/auto\/upload' " .
                   "name='file' type='file'\/>/";
        self::assertMatchesRegularExpression($pattern, (string)UploadTag::fromParams('image', ['html' => ['class' => 'classy']]));
    }

    /**
     * @param       $source
     * @param       $options
     * @param       $expected
     */
    private static function assertCloudinaryUrl($source, $options, $expected)
    {
        $url = Media::fromParams($source, $options);
        self::assertEquals($expected, $url);
    }

    /**
     * @param string $tagName               Expected tag name(img or source)
     * @param string $publicId              Public ID of the image
     * @param string $commonTransStr        Default transformation string to be used in all resources
     * @param string $customTransStr        Optional custom transformation string to be be used inside srcset resources
     *                                      If not provided, $common_trans_str is used
     * @param array  $srcsetBreakpoints     Optional list of breakpoints for srcset. If not provided srcset is omitted
     * @param array  $attributes            Associative array of custom attributes to be added to the tag
     *
     * @param bool   $isVoid                Indicates whether tag is an HTML5 void tag (does not need to be self-closed)
     *
     * @return string Resulting tag
     * @internal
     * Helper method for generating expected `img` and `source` tags
     *
     */
    private static function commonImageTagFromParamsHelper(
        $tagName,
        $publicId,
        $commonTransStr,
        $customTransStr = '',
        $srcsetBreakpoints = [],
        $attributes = [],
        $isVoid = false
    ) {
        if (empty($customTransStr)) {
            $customTransStr = $commonTransStr;
        }

        if (! empty($srcsetBreakpoints)) {
            $single_srcset_image  = static function ($w) use ($customTransStr, $publicId) {
                return self::DEFAULT_UPLOAD_PATH . "{$customTransStr}/c_scale,w_{$w}/{$publicId} {$w}w";
            };
            $attributes['srcset'] = implode(', ', array_map($single_srcset_image, $srcsetBreakpoints));
        }

        $tag = "<$tagName";

        $attributesStr = implode(
            ' ',
            array_map(
                static function ($k, $v) {
                    return "$k='$v'";
                },
                array_keys($attributes),
                array_values($attributes)
            )
        );

        if (! empty($attributesStr)) {
            $tag .= " {$attributesStr}";
        }

        $tag .= $isVoid ? '>' : '/>'; // HTML5 void elements do not need to be self closed

        if (getenv('DEBUG')) {
            echo preg_replace('/([,\']) /', "$1\n    ", $tag) . "\n\n";
        }

        return $tag;
    }

    /**
     * @param string $publicId              Public ID of the image
     * @param string $commonTransStr        Default transformation string to be used in all resources
     * @param string $customTransStr        Optional custom transformation string to be be used inside srcset resources
     *                                      If not provided, $common_trans_str is used
     * @param array  $srcsetBreakpoints     Optional list of breakpoints for srcset. If not provided srcset is omitted
     * @param array  $attributes            Associative array of custom attributes to be added to the tag
     *
     * @return string Resulting image tag
     * @internal
     * Helper method for test_cl_image_tag_srcset for generating expected image tag
     *
     */
    private static function expectedImageTagFromParams(
        $publicId,
        $commonTransStr,
        $customTransStr = '',
        $srcsetBreakpoints = [],
        $attributes = []
    ) {
        ArrayUtils::prependAssoc(
            $attributes,
            'src',
            self::DEFAULT_UPLOAD_PATH . "{$commonTransStr}/{$publicId}"
        );

        return self::commonImageTagFromParamsHelper(
            'img',
            $publicId,
            $commonTransStr,
            $customTransStr,
            $srcsetBreakpoints,
            $attributes
        );
    }

    /**
     * @param string $publicId              Public ID of the image
     * @param string $commonTransStr        Default transformation string to be used in all resources
     * @param string $customTransStr        Optional custom transformation string to be be used inside srcset resources
     *                                      If not provided, $common_trans_str is used
     * @param array  $srcsetBreakpoints     Optional list of breakpoints for srcset. If not provided srcset is omitted
     * @param array  $attributes            Associative array of custom attributes to be added to the tag
     *
     * @return string Resulting `source` tag
     * @internal
     * Helper method for for generating expected `source` tag
     *
     */
    private static function expectedSourceTagFromParams(
        $publicId,
        $commonTransStr,
        $customTransStr = '',
        $srcsetBreakpoints = [],
        $attributes = []
    ) {
        $attributes['srcset'] = self::DEFAULT_UPLOAD_PATH . "{$commonTransStr}/{$publicId }";

        ksort($attributes); // Used here to produce output similar to Cloudinary::html_attrs

        return self::commonImageTagFromParamsHelper(
            "source",
            $publicId,
            $commonTransStr,
            $customTransStr,
            $srcsetBreakpoints,
            $attributes,
            true
        );
    }
}
