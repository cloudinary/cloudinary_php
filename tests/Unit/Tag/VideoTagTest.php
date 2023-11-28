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

use Cloudinary\Asset\Video;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\UrlConfig;
use Cloudinary\Tag\VideoTag;
use Cloudinary\Transformation\Angle;
use Cloudinary\Transformation\Rotate;
use Cloudinary\Transformation\Scale;
use Cloudinary\Transformation\Transformation;

/**
 * Class VideoTagTest
 */
final class VideoTagTest extends TagTestCase
{
    const VIDEO_URL_PREFIX        = 'https://res.cloudinary.com/test123/video/';
    const FETCH_VIDEO_URL_PREFIX  = self::VIDEO_URL_PREFIX . 'fetch/';
    const UPLOAD_VIDEO_URL_PREFIX = self::VIDEO_URL_PREFIX . 'upload/';

    protected $video;

    protected $defaultSourcesStr
        = '<source src="' . self::UPLOAD_VIDEO_URL_PREFIX . 'vc_h265/sample.mp4"' .
          ' type="video/mp4; codecs=hev1">' . "\n" .
          '<source src="' . self::UPLOAD_VIDEO_URL_PREFIX . 'vc_vp9/sample.webm"' .
          ' type="video/webm; codecs=vp9">' . "\n" .
          '<source src="' . self::UPLOAD_VIDEO_URL_PREFIX . 'vc_auto/sample.mp4" type="video/mp4">' . "\n" .
          '<source src="' . self::UPLOAD_VIDEO_URL_PREFIX . 'vc_auto/sample.webm" type="video/webm">';

    protected $posterAttr = 'poster="https://res.cloudinary.com/test123/video/upload/sample.jpg"';

    public function setUp()
    {
        parent::setUp();

        $this->video = new Video(self::VIDEO_NAME);
    }

    public function testVideoTag()
    {
        $tag = new VideoTag(self::VIDEO_NAME);

        $expected = "<video {$this->posterAttr}>\n{$this->defaultSourcesStr}\n</video>";

        self::assertEquals(
            (string)$expected,
            (string)$tag
        );

        self::assertEquals(
            (string)$expected,
            (string)VideoTag::upload(self::VIDEO_NAME)
        );
    }

    public function testVideoTagFetchVideo()
    {
        $videoUrl  = self::FETCH_VIDEO_URL;
        $prefixUrl = self::FETCH_VIDEO_URL_PREFIX;

        $expected = "<video poster=\"{$prefixUrl}f_jpg/$videoUrl\">" . "\n" .
                    "<source src=\"{$prefixUrl}f_mp4/vc_h265/$videoUrl\" type=\"video/mp4; codecs=hev1\">" . "\n" .
                    "<source src=\"{$prefixUrl}f_webm/vc_vp9/$videoUrl\" type=\"video/webm; codecs=vp9\">" . "\n" .
                    "<source src=\"{$prefixUrl}f_mp4/vc_auto/$videoUrl\" type=\"video/mp4\">" . "\n" .
                    "<source src=\"{$prefixUrl}f_webm/vc_auto/$videoUrl\" type=\"video/webm\">" . "\n" .
                    '</video>';

        self::assertStrEquals(
            $expected,
            VideoTag::fetch(self::FETCH_VIDEO_URL)
        );

        self::assertStrEquals(
            $expected,
            new VideoTag(Video::fetch(self::FETCH_VIDEO_URL))
        );
    }

    public function testVideoTagUseFetchFormat()
    {
        $videoPubId = self::FOLDER . '/' . self::VIDEO_NAME;
        $urlPref     = self::UPLOAD_VIDEO_URL_PREFIX;

        $expected = "<video poster=\"{$urlPref}f_jpg/v1/$videoPubId\">" . "\n" .
                    "<source src=\"{$urlPref}f_mp4/vc_h265/v1/$videoPubId\" type=\"video/mp4; codecs=hev1\">" . "\n" .
                    "<source src=\"{$urlPref}f_webm/vc_vp9/v1/$videoPubId\" type=\"video/webm; codecs=vp9\">" . "\n" .
                    "<source src=\"{$urlPref}f_mp4/vc_auto/v1/$videoPubId\" type=\"video/mp4\">" . "\n" .
                    "<source src=\"{$urlPref}f_webm/vc_auto/v1/$videoPubId\" type=\"video/webm\">" . "\n" .
                    '</video>';

        self::assertStrEquals(
            $expected,
            VideoTag::upload($videoPubId)->useFetchFormat()
        );

        $conf = Configuration::instance();
        $conf->tag->useFetchFormat();

        self::assertStrEquals(
            $expected,
            VideoTag::upload($videoPubId, $conf)
        );
    }

    public function testVideoTagWithTransformation()
    {
        $video = (new Video(self::VIDEO_NAME))->rotate(Rotate::byAngle(17))->resize(Scale::scale(500));
        $tag   = new VideoTag($video, []);

        $expectedPoster = 'https://res.cloudinary.com/test123/video/upload/a_17/c_scale,w_500/sample.jpg';
        $expected       = "<video src=\"$video\" poster=\"{$expectedPoster}\"></video>";

        self::assertEquals(
            (string)$expected,
            (string)$tag
        );
    }

    public function testVideoTagBuilders()
    {
        $host      = UrlConfig::DEFAULT_SHARED_HOST;
        $cloudName = 'test321';
        $trStr     = (new Transformation())->rotate(Rotate::byAngle(17))->resize(Scale::scale(500))->toUrl();
        $ver       = 17;
        $assetType = 'video/upload';
        $publicId  = self::ASSET_ID;

        $tag = (new VideoTag(self::VIDEO_NAME))
            ->rotate(Rotate::byAngle(17)) // transformation
            ->resize(Scale::scale(500))
            ->cloudName($cloudName) // cloud config
            ->secure(false) // url config
            ->version($ver); // asset descriptor

        $expectedPoster = "http://{$host}/{$cloudName}/{$assetType}/{$trStr}/v{$ver}/{$publicId}.jpg";
        // TODO: make a good template for all video tag tests and reuse it.
        $sourcesStr = str_replace(
            [self::PROTOCOL_HTTPS, self::CLOUD_NAME, "/{$assetType}/", "/{$publicId}"],
            [self::PROTOCOL_HTTP, $cloudName, "/{$assetType}/{$trStr}/", "/v$ver/{$publicId}"],
            $this->defaultSourcesStr
        );

        $expected = "<video poster=\"{$expectedPoster}\">\n{$sourcesStr}\n</video>";

        self::assertStrEquals(
            $expected,
            $tag
        );
    }

    public function testVideoTagNoSources()
    {
        $tag = new VideoTag(self::VIDEO_NAME, []);

        $expected = "<video src=\"{$this->video}\" $this->posterAttr></video>";
        self::assertEquals(
            (string)$expected,
            (string)$tag
        );
    }

    public function testVideoTagAssetConfigurationBuilders()
    {
        $tag = new VideoTag(self::VIDEO_NAME, []);

        $customPosterAttr = str_replace(self::CLOUD_NAME, self::CUSTOM_CLOUD_NAME, $this->posterAttr);
        $expected         = "<video src=\"{$this->video->cloudName(self::CUSTOM_CLOUD_NAME)}\" $customPosterAttr>" .
                            '</video>';
        self::assertEquals(
            $expected,
            (string)$tag->cloudName(self::CUSTOM_CLOUD_NAME)
        );
    }

    public function testVideoTagWithFallback()
    {
        $fallback = '<span id="spanid">Cannot display video</span>';

        $tag = (new VideoTag(self::VIDEO_NAME))->fallback($fallback);

        $expected = "<video {$this->posterAttr}>\n{$this->defaultSourcesStr}\n{$fallback}\n</video>";
        self::assertStrEquals(
            $expected,
            $tag
        );
    }

    public function testVideoTagWithAttributes()
    {
        $attributes = [
            'autoplay' => true,
            'preload'  => false,
            'controls',
            'loop',
            'muted'    => 'true',
            'style'    => 'border: 1px',
        ];

        $tag = (new VideoTag(self::VIDEO_NAME, []))->setAttributes($attributes);

        $expected = "<video autoplay controls loop muted=\"true\" style=\"border: 1px\" src=\"{$this->video}\" " .
                    "$this->posterAttr>" .
                    '</video>';
        self::assertEquals(
            $expected,
            (string)$tag
        );
    }
}
