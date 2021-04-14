<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Admin;

use Cloudinary\Api\Admin\AdminApi;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Configuration\ConfigUtils;
use Cloudinary\Test\Integration\IntegrationTestCase;

/**
 * Class OAuthTest
 */
final class OAuthTest extends IntegrationTestCase
{
    const FAKE_OAUTH_TOKEN = 'MTQ0NjJkZmQ5OTM2NDE1ZTZjNGZmZjI4';

    private static $UNIQUE_IMAGE_PUBLIC_ID;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$UNIQUE_IMAGE_PUBLIC_ID = 'asset_image_' . self::$UNIQUE_TEST_ID;
    }

    public function testOAuthToken()
    {
        $config = new Configuration(Configuration::instance());
        $config->cloud->oauthToken(self::FAKE_OAUTH_TOKEN);
        $adminApi = new AdminApi($config);

        $this->expectExceptionMessage('Invalid token');

        $adminApi->asset(self::$UNIQUE_IMAGE_PUBLIC_ID);
    }
}
