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

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Unit\UnitTestCase;
use InvalidArgumentException;

/**
 * Class CloudinaryTest
 */
class CloudinaryTest extends UnitTestCase
{
    public function testCloudinaryUrlFromEnv()
    {
        $c = new Cloudinary();

        self::assertNotNull($c->configuration->cloud->cloudName);
        self::assertNotNull($c->configuration->cloud->apiKey);
        self::assertNotNull($c->configuration->cloud->apiSecret);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCloudinaryUrlNotSet()
    {
        self::clearEnvironment();

        new Cloudinary(); // Boom!
    }

    public function testCloudinaryFromOptions()
    {
        $c = new Cloudinary(
            [
                'cloud' => [
                    'cloud_name' => self::CLOUD_NAME,
                    'api_key'    => self::API_KEY,
                    'api_secret' => self::API_SECRET,
                ],
            ]
        );

        self::assertEquals(self::CLOUD_NAME, $c->configuration->cloud->cloudName);
        self::assertEquals(self::API_KEY, $c->configuration->cloud->apiKey);
        self::assertEquals(self::API_SECRET, $c->configuration->cloud->apiSecret);
    }

    public function testCloudinaryFromUrl()
    {
        $c = new Cloudinary($this->cloudinaryUrl);

        self::assertEquals(self::CLOUD_NAME, $c->configuration->cloud->cloudName);
        self::assertEquals(self::API_KEY, $c->configuration->cloud->apiKey);
        self::assertEquals(self::API_SECRET, $c->configuration->cloud->apiSecret);
    }

    public function testCloudinaryFromConfiguration()
    {
        self::clearEnvironment();

        $config = new Configuration($this->cloudinaryUrl);

        $c = new Cloudinary($config);

        self::assertEquals(self::CLOUD_NAME, $c->configuration->cloud->cloudName);
        self::assertEquals(self::API_KEY, $c->configuration->cloud->apiKey);
        self::assertEquals(self::API_SECRET, $c->configuration->cloud->apiSecret);
    }
}
