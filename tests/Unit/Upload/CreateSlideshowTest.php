<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Upload;

use Cloudinary\Test\Helpers\MockUploadApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Quality;
use Cloudinary\Transformation\Transformation;

/**
 * Class CreateSlideshowTest
 */
final class CreateSlideshowTest extends AssetTestCase
{
    use RequestAssertionsTrait;

    private $slideshowManifest
        = 'w_352;h_240;du_5;fps_30;vars_(slides_((media_s64:aHR0cHM6Ly9y' .
          'ZXMuY2xvdWRpbmFyeS5jb20vZGVtby9pbWFnZS91cGxvYWQvY291cGxl);(media_s64:aH' .
          'R0cHM6Ly9yZXMuY2xvdWRpbmFyeS5jb20vZGVtby9pbWFnZS91cGxvYWQvc2FtcGxl)))';
    private $slideshowManifestJson
        = [
            "w"    => 848,
            "h"    => 480,
            "du"   => 6,
            "fps"  => 30,
            "vars" => [
                "sdur"   => 500,
                "tdur"   => 500,
                "slides" => [
                    ["media" => "i:protests9"],
                    ["media" => "i:protests8"],
                    ["media" => "i:protests7"],
                    ["media" => "i:protests6"],
                    ["media" => "i:protests2"],
                    ["media" => "i:protests1"],
                ],
            ],
        ];

    private $slideshowManifestJsonStr
        = '{"w":848,"h":480,"du":6,"fps":30,"vars":{"sdur":500,"tdur":500,"slides":[{"media":"i:protests9"},' .
          '{"media":"i:protests8"},{"media":"i:protests7"},{"media":"i:protests6"},{"media":"i:protests2"},' .
          '{"media":"i:protests1"}]}}';

    const NOTIFICATION_URL = 'https://notification-url.com';
    const UPLOAD_PRESET    = 'create_slideshow_preset';

    /**
     * Creates a slideshow from the specified manifest.
     */
    public function testCreateSlideshow()
    {
        $mockUploadApi = new MockUploadApi();

        $mockUploadApi->createSlideshow([
            'manifest_transformation' => [
                'custom_function' => [
                    'function_type' => 'render',
                    'source'        => $this->slideshowManifest,
                ],
            ],
            'transformation'          => (new Transformation())->format(Format::auto())->quality(Quality::auto()),
            'manifest_json'           => $this->slideshowManifestJson,
            'tags'                    => ['tag1', 'tag2', 'tag3'],
            'overwrite'               => true,
            'public_id'               => self::ASSET_ID,
            'notification_url'        => self::NOTIFICATION_URL,
            'upload_preset'           => self::UPLOAD_PRESET,
        ]);

        $lastRequest = $mockUploadApi->getMockHandler()->getLastRequest();

        self::assertRequestUrl($lastRequest, '/video/create_slideshow');

        self::assertRequestBodySubset(
            $lastRequest,
            [
                'manifest_transformation' => "fn_render:$this->slideshowManifest",
                'manifest_json'           => $this->slideshowManifestJsonStr,
                'transformation'          => 'f_auto/q_auto',
                'tags'                    => 'tag1,tag2,tag3',
                'overwrite'               => '1',
                'public_id'               => self::ASSET_ID,
                'notification_url'        => self::NOTIFICATION_URL,
                'upload_preset'           => self::UPLOAD_PRESET,
            ]
        );

    }

}
