<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Configuration\Provisioning;

use Cloudinary\Configuration\Provisioning\ProvisioningConfiguration;
use Cloudinary\Test\Unit\Provisioning\ProvisioningUnitTestCase;

/**
 * Class ConfigurationAccountTest
 */
class ConfigurationAccountTest extends ProvisioningUnitTestCase
{
    public function testAccountConfigFromUrl()
    {
        $config = new ProvisioningConfiguration($this->accountUrl);

        self::assertEquals(self::ACCOUNT_ID, $config->provisioningAccount->accountId);
        self::assertEquals(self::ACCOUNT_API_KEY, $config->provisioningAccount->provisioningApiKey);
        self::assertEquals(self::ACCOUNT_API_SECRET, $config->provisioningAccount->provisioningApiSecret);
    }

    public function testAccountConfigFromArray()
    {
        $config = new ProvisioningConfiguration(
            [
                'account_id'              => self::ACCOUNT_ID,
                'provisioning_api_key'    => self::ACCOUNT_API_KEY,
                'provisioning_api_secret' => self::ACCOUNT_API_SECRET,
            ]
        );

        self::assertEquals(self::ACCOUNT_ID, $config->provisioningAccount->accountId);
        self::assertEquals(self::ACCOUNT_API_KEY, $config->provisioningAccount->provisioningApiKey);
        self::assertEquals(self::ACCOUNT_API_SECRET, $config->provisioningAccount->provisioningApiSecret);
    }

    public function testAccountConfigFromObject()
    {
        $config = new ProvisioningConfiguration($this->accountUrl);
        $config = new ProvisioningConfiguration($config);

        self::assertEquals(self::ACCOUNT_ID, $config->provisioningAccount->accountId);
        self::assertEquals(self::ACCOUNT_API_KEY, $config->provisioningAccount->provisioningApiKey);
        self::assertEquals(self::ACCOUNT_API_SECRET, $config->provisioningAccount->provisioningApiSecret);
    }

    public function testAccountConfigJsonSerialize()
    {
        $config = new ProvisioningConfiguration($this->accountUrl);

        $jsonConfig         = json_encode($config->jsonSerialize());
        $expectedJsonConfig = '{"provisioning_account":{"account_id":"' . self::ACCOUNT_ID .
                              '","provisioning_api_key":"' . self::ACCOUNT_API_KEY . '","provisioning_api_secret":"' .
                              self::ACCOUNT_API_SECRET . '"}}';

        self::assertEquals($expectedJsonConfig, $jsonConfig);
    }
}
