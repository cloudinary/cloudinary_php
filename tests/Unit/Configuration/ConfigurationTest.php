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

/**
 * Class ConfigTest
 */
class ConfigurationTest extends UnitTestCase
{

    public function testConfigFromUrl()
    {
        $config = new Configuration($this->cloudinaryUrl);

        $this->assertEquals(self::CLOUD_NAME, $config->account->cloudName);
        $this->assertEquals(self::API_KEY, $config->account->apiKey);
        $this->assertEquals(self::API_SECRET, $config->account->apiSecret);
    }

    public function testConfigToString()
    {
        $config = Configuration::fromCloudinaryUrl($this->cloudinaryUrl);

        $config->url->secureDistribution = 'my_distribution';
        $config->url->cname              = 'my.domain.com';

        $this->assertStrEquals(
            $this->cloudinaryUrl . '/my_distribution?url[cname]=my.domain.com',
            $config
        );
    }

    public function testConfigJsonSerialize()
    {
        $jsonConfig = json_encode(Configuration::fromCloudinaryUrl($this->cloudinaryUrl));

        $expectedJsonConfig = '{"version":' . Configuration::VERSION . ',"account":{' .
                              '"cloud_name":"' . self::CLOUD_NAME . '","api_key":"' . self::API_KEY . '","api_secret":"'
                              . self::API_SECRET . '"}}';

        $this->assertEquals(
            $expectedJsonConfig,
            $jsonConfig
        );

        $this->assertEquals(
            $expectedJsonConfig,
            json_encode(Configuration::fromJson($expectedJsonConfig))
        );
    }
}
