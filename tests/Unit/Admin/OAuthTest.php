<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Admin;

use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Helpers\MockAdminApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Unit\UnitTestCase;
use InvalidArgumentException;

/**
 * Class OAuthTest
 */
final class OAuthTest extends UnitTestCase
{
    use RequestAssertionsTrait;

    const FAKE_OAUTH_TOKEN = 'MTQ0NjJkZmQ5OTM2NDE1ZTZjNGZmZjI4';

    /**
     * Should make a request using an Oauth Token.
     */
    public function testOauthTokenAdminApi()
    {
        $config = new Configuration(Configuration::instance());
        $config->cloud->oauthToken(self::FAKE_OAUTH_TOKEN);

        $adminApi = new MockAdminApi($config);
        $adminApi->ping();
        $lastRequest = $adminApi->getMockHandler()->getLastRequest();

        self::assertRequestHeaderSubset(
            $lastRequest,
            [
                'Authorization' => ['Bearer ' . self::FAKE_OAUTH_TOKEN]
            ]
        );
    }

    /**
     * Should make a request using `apiKey` and `apiSecret` if an Oauth Token is absent.
     */
    public function testKeyAndSecretAdminApi()
    {
        $config = new Configuration(Configuration::instance());
        $config->cloud->oauthToken(null);

        $adminApi = new MockAdminApi($config);
        $adminApi->ping();
        $lastRequest = $adminApi->getMockHandler()->getLastRequest();

        self::assertRequestHeaderSubset(
            $lastRequest,
            [
                'Authorization' => ['Basic a2V5OnNlY3JldA==']
            ]
        );
    }

    /**
     * Should be thrown an exception if `apiKey` and `apiSecret` or an Oauth Token are absent.
     */
    public function testMissingCredentialsAdminApi()
    {
        $config = new Configuration(Configuration::instance());
        $config->cloud->oauthToken(null);
        $config->cloud->apiKey = null;
        $config->cloud->apiSecret = null;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Must supply apiKey');

        $adminApi = new MockAdminApi($config);
        $adminApi->ping();
    }
}
