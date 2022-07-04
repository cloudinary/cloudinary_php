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

use Cloudinary\Configuration\Configuration;

/**
 * Class AuthTokenTestCase
 */
class AuthTokenTestCase extends AssetTestCase
{
    const AUTH_TOKEN_KEY     = '00112233FF99';
    const AUTH_TOKEN_ALT_KEY = 'CCBB2233FF00';

    const DURATION   = 300;
    const START_TIME = 11111111;

    const AUTH_TOKEN_TEST_IMAGE      = 'sample.jpg';
    const AUTH_TOKEN_TEST_CONFIG_ACL = '/*/t_foobar';
    const AUTH_TOKEN_TEST_PATH       = 'http://res.cloudinary.com/test123/image/upload/v1486020273/sample.jpg';

    public function setUp()
    {
        parent::setUp();

        Configuration::instance()->importCloudinaryUrl(
            $this->cloudinaryUrl.
            '?auth_token[duration]='.self::DURATION.
            '&auth_token[start_time]='.static::START_TIME.
            '&auth_token[key]='.self::AUTH_TOKEN_KEY.
            '&url[sign_url]=true'.
            '&url[private_cdn]=true'
        );
    }
}
