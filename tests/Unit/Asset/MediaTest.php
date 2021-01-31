<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Asset;

use Cloudinary\Asset\DeliveryType;
use Cloudinary\Asset\Media;
use Cloudinary\Transformation\AudioCodec;
use Cloudinary\Transformation\Gravity;
use UnexpectedValueException;

/**
 * Class MediaTest
 */
final class MediaTest extends AssetTestCase
{
    /**
     * @var Media $media Test image that is commonly reused by tests
     */
    protected $media;

    public function setUp()
    {
        parent::setUp();

        $this->media = new Media(self::IMAGE_NAME);
    }

    public function testSimpleMedia()
    {
        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->media
        );

        self::assertImageUrl(
            self::IMAGE_NAME,
            $this->media->toUrl()
        );
    }

    public function testFetchMedia()
    {
        $image = Media::fetch(self::FETCH_IMAGE_URL);

        self::assertImageUrl(self::FETCH_IMAGE_URL, $image, ['delivery_type' => DeliveryType::FETCH]);
    }

    public function testMediaAssetProperties()
    {
        self::assertAssetUrl(
            'images/' . self::TEST_ASSET_VERSION_STR . '/' . self::URL_SUFFIXED_ASSET_ID . '.' . self::IMG_EXT_GIF,
            $this->media
                ->version(self::TEST_ASSET_VERSION)
                ->suffix(self::URL_SUFFIX)
                ->extension(self::IMG_EXT_GIF)
        );
    }

    /**
     * @expectedException UnexpectedValueException
     */
    public function testMediaAssetSuffixValidation()
    {
        $this->media->suffix('../illegal_suffix.//');
    }

    public function testMediaMultipleTypes()
    {
        self::assertImageUrl(
            'c_fill,g_auto,h_160,w_80/ac_vorbis/' . self::IMAGE_NAME,
            $this->media->fill(80, 160, Gravity::auto())->transcode(AudioCodec::vorbis())
        );
    }
}
