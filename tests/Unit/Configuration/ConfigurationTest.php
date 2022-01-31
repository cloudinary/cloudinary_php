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

use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Unit\UnitTestCase;
use Cloudinary\Utils;

/**
 * Class ConfigTest
 */
class ConfigurationTest extends UnitTestCase
{
    const OAUTH_TOKEN          = 'NTQ0NjJkZmQ5OTM2NDE1ZTZjNGZmZj17';
    const URL_WITH_OAUTH_TOKEN = 'cloudinary://' . self::CLOUD_NAME . '?cloud[oauth_token]=' . self::OAUTH_TOKEN;

    public function testConfigFromUrl()
    {
        $config = new Configuration($this->cloudinaryUrl);

        self::assertEquals(self::CLOUD_NAME, $config->cloud->cloudName);
        self::assertEquals(self::API_KEY, $config->cloud->apiKey);
        self::assertEquals(self::API_SECRET, $config->cloud->apiSecret);
    }

    /**
     * Should allow passing Cloudinary URL that starts with a 'CLOUDINARY_URL=' prefix, which is technically illegal,
     * but we are permissive.
     */
    public function testConfigFromFullUrl()
    {
        $config = new Configuration(Configuration::CLOUDINARY_URL_ENV_VAR . '=' . $this->cloudinaryUrl);

        self::assertEquals(self::CLOUD_NAME, $config->cloud->cloudName);
        self::assertEquals(self::API_KEY, $config->cloud->apiKey);
        self::assertEquals(self::API_SECRET, $config->cloud->apiSecret);
    }

    public function testConfigFromUrlsWithoutKeyAndSecretButWithOAuthToken()
    {
        $config = new Configuration(self::URL_WITH_OAUTH_TOKEN);

        self::assertEquals(self::CLOUD_NAME, $config->cloud->cloudName);
        self::assertEquals(self::OAUTH_TOKEN, $config->cloud->oauthToken);
        self::assertNull($config->cloud->apiKey);
        self::assertNull($config->cloud->apiSecret);
    }

    public function testConfigFromUrlsWitKeyAndSecretAndOAuthToken()
    {
        $config = new Configuration($this->cloudinaryUrl . '?cloud[oauth_token]=' . self::OAUTH_TOKEN);

        self::assertEquals(self::CLOUD_NAME, $config->cloud->cloudName);
        self::assertEquals(self::API_KEY, $config->cloud->apiKey);
        self::assertEquals(self::API_SECRET, $config->cloud->apiSecret);
        self::assertEquals(self::OAUTH_TOKEN, $config->cloud->oauthToken);
    }

    public function testConfigNoEnv()
    {
        self::clearEnvironment();

        $config = new Configuration();

        $config->cloud->cloudName(self::CLOUD_NAME);

        self::assertEquals(self::CLOUD_NAME, $config->cloud->cloudName);
    }

    public function testConfigToString()
    {
        $config = Configuration::fromCloudinaryUrl($this->cloudinaryUrl);

        $config->url->secureCname = 'my_distribution';
        $config->url->cname       = 'my.domain.com';

        $config->cloud->signatureAlgorithm(Utils::ALGO_SHA256);

        self::assertStrEquals(
            $this->cloudinaryUrl . '/my_distribution?cloud[signature_algorithm]=sha256&url[cname]=my.domain.com',
            $config
        );
    }

    public function testConfigToStringWithOAuthToken()
    {
        $config = Configuration::fromCloudinaryUrl($this->cloudinaryUrl);

        $config->cloud->oauthToken = self::OAUTH_TOKEN;

        self::assertStrEquals(
            $this->cloudinaryUrl . '?cloud[oauth_token]=' . self::OAUTH_TOKEN,
            $config
        );
    }

    public function testConfigToStringWithMultipleQueryParams()
    {
        $config = Configuration::fromCloudinaryUrl($this->cloudinaryUrl);

        $config->url->secureCname = 'my_another_distribution';
        $config->url->cname       = 'my.another-domain.com';

        $config->cloud->oauthToken = self::OAUTH_TOKEN;

        self::assertStrEquals(
            $this->cloudinaryUrl . '/my_another_distribution' .
            '?cloud[oauth_token]=' . self::OAUTH_TOKEN .
            '&url[cname]=my.another-domain.com',
            $config
        );
    }

    public function testConfigKeyExplicitlySet()
    {
        $config = Configuration::fromCloudinaryUrl($this->cloudinaryUrl);

        self::assertTrue($config->cloud->isExplicitlySet('cloud_name'));

        self::assertTrue($config->url->secure); // configuration default is set to true.
        self::assertFalse($config->url->isExplicitlySet('secure')); // it was not set by user.

        // set the property
        $config->url->secure = false;

        self::assertTrue($config->url->isExplicitlySet('secure'));
    }

    public function testConfigJsonSerialize()
    {
        $jsonConfig = json_encode(Configuration::fromCloudinaryUrl($this->cloudinaryUrl));

        $expectedJsonConfig = '{"version":' . Configuration::VERSION . ',"cloud":{' .
                              '"cloud_name":"' . self::CLOUD_NAME . '","api_key":"' . self::API_KEY . '","api_secret":"'
                              . self::API_SECRET . '"}}';

        self::assertEquals(
            $expectedJsonConfig,
            $jsonConfig
        );

        self::assertEquals(
            $expectedJsonConfig,
            json_encode(Configuration::fromJson($expectedJsonConfig))
        );
    }
}
