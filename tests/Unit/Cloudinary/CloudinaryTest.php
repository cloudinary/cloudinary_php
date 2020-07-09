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

        $this->assertNotNull($c->configuration->account->cloudName);
        $this->assertNotNull($c->configuration->account->apiKey);
        $this->assertNotNull($c->configuration->account->apiSecret);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testCloudinaryUrlNotSet()
    {
        putenv(Configuration::CLOUDINARY_URL_ENV_VAR); // unset CLOUDINARY_URL
        new Cloudinary(); // Boom!
    }

    public function testCloudinaryFromOptions()
    {
        $c = new Cloudinary(
            [
                'account' => [
                    'cloud_name' => self::CLOUD_NAME,
                    'api_key'        => self::API_KEY,
                    'api_secret'     => self::API_SECRET,
                ],
            ]
        );

        $this->assertEquals(self::CLOUD_NAME, $c->configuration->account->cloudName);
        $this->assertEquals(self::API_KEY, $c->configuration->account->apiKey);
        $this->assertEquals(self::API_SECRET, $c->configuration->account->apiSecret);
    }

    public function testCloudinaryFromUrl()
    {
        $c = new Cloudinary($this->cloudinaryUrl);

        $this->assertEquals(self::CLOUD_NAME, $c->configuration->account->cloudName);
        $this->assertEquals(self::API_KEY, $c->configuration->account->apiKey);
        $this->assertEquals(self::API_SECRET, $c->configuration->account->apiSecret);
    }
}
