<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Cloudinary;

use Cloudinary\Asset\DeliveryType;
use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Unit\Asset\AssetTestCase;

/**
 * Class CloudinaryAssetTest
 */
class CloudinaryAssetTest extends AssetTestCase
{
    /**
     * @var Cloudinary $c Client instance
     */
    private $c;

    public function setUp()
    {
        parent::setUp();

        $this->c = new Cloudinary(Configuration::instance());
    }

    public function testSimpleCloudinaryImage()
    {
        $image = $this->c->image(self::IMAGE_NAME);

        self::assertImageUrl(
            self::IMAGE_NAME,
            $image
        );
    }

    public function testSimpleCloudinaryVideo()
    {
        $image = $this->c->video(self::VIDEO_NAME);

        self::assertVideoUrl(
            self::VIDEO_NAME,
            $image
        );
    }

    public function testSimpleCloudinaryRaw()
    {
        $image = $this->c->raw(self::FILE_NAME);

        self::assertFileUrl(
            self::FILE_NAME,
            $image
        );
    }

    public function testCloudinaryImageWithCustomCloudinaryConfig()
    {

        $this->c->configuration->url->secureCname(self::TEST_HOSTNAME);

        $image = $this->c->image(self::IMAGE_NAME);

        self::assertImageUrl(
            self::IMAGE_NAME,
            $image,
            [
                'hostname' => self::TEST_HOSTNAME,
            ]
        );
    }

    public function testCloudinaryImageWithCustomImageConfig()
    {
        $image = $this->c->image(self::IMAGE_NAME);

        $image->secureCname(self::TEST_HOSTNAME);

        self::assertImageUrl(
            self::IMAGE_NAME,
            $image,
            [
                'hostname' => self::TEST_HOSTNAME,
            ]
        );
    }

    public function testCloudinaryImageFetch()
    {
        $image = $this->c->image(self::FETCH_IMAGE_URL);

        $image->deliveryType(DeliveryType::FETCH);

        self::assertImageUrl(
            self::FETCH_IMAGE_URL,
            $image,
            ['delivery_type' => DeliveryType::FETCH]
        );
    }
}
