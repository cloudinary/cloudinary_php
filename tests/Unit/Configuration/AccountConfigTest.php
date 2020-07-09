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

use Cloudinary\Configuration\AccountConfig;
use Cloudinary\Test\Unit\UnitTestCase;

/**
 * Class AccountTest
 */
class AccountConfigTest extends UnitTestCase
{
    public function testAccountFromUrl()
    {
        $account = AccountConfig::fromCloudinaryUrl($this->cloudinaryUrl);

        $this->assertEquals(self::CLOUD_NAME, $account->cloudName);

        $this->assertEquals(
            '',
            (string)$account
        );

        $this->assertEquals(
            '{"account":{"cloud_name":"' . self::CLOUD_NAME . '","api_key":"' . self::API_KEY . '","api_secret":"' .
            self::API_SECRET . '"}}',
            json_encode($account)
        );

        $this->assertEquals(
            '{"account":{"cloud_name":"' . self::CLOUD_NAME . '"}}',
            json_encode($account->jsonSerialize(false)) // exclude sensitive (passwords, etc) keys
        );
    }
}
