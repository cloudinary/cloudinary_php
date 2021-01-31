<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Configuration;

use Cloudinary\Configuration\CloudConfig;
use Cloudinary\Test\Unit\UnitTestCase;

/**
 * Class CloudConfigTest
 */
class CloudConfigTest extends UnitTestCase
{
    public function testCloudFromUrl()
    {
        $cloud = CloudConfig::fromCloudinaryUrl($this->cloudinaryUrl);

        self::assertEquals(self::CLOUD_NAME, $cloud->cloudName);

        self::assertEquals(
            '',
            (string)$cloud
        );

        self::assertEquals(
            '{"cloud":{"cloud_name":"' . self::CLOUD_NAME . '","api_key":"' . self::API_KEY . '","api_secret":"' .
            self::API_SECRET . '"}}',
            json_encode($cloud)
        );

        self::assertEquals(
            '{"cloud":{"cloud_name":"' . self::CLOUD_NAME . '"}}',
            json_encode($cloud->jsonSerialize(false)) // exclude sensitive (passwords, etc) keys
        );
    }
}
