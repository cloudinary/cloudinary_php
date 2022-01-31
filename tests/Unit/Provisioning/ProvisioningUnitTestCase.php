<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Provisioning;

use Cloudinary\Configuration\Provisioning\ProvisioningConfiguration;
use Cloudinary\Test\CloudinaryTestCase;

/**
 * Class ProvisioningUnitTestCase
 */
abstract class ProvisioningUnitTestCase extends CloudinaryTestCase
{

    const ACCOUNT_ID         = 'account123';
    const ACCOUNT_API_KEY    = 'accountKey';
    const ACCOUNT_API_SECRET = 'accountSecret';

    protected $accountUrl;

    private $accountUrlEnvBackup;

    public function setUp()
    {
        parent::setUp();

        $this->accountUrlEnvBackup = getenv(ProvisioningConfiguration::CLOUDINARY_ACCOUNT_URL_ENV_VAR);

        $this->accountUrl = 'account://' . $this::ACCOUNT_API_KEY . ':' . $this::ACCOUNT_API_SECRET . '@'
                            . $this::ACCOUNT_ID;

        putenv(ProvisioningConfiguration::CLOUDINARY_ACCOUNT_URL_ENV_VAR . '=' . $this->accountUrl);
    }

    public function tearDown()
    {
        parent::tearDown();

        putenv(ProvisioningConfiguration::CLOUDINARY_ACCOUNT_URL_ENV_VAR . '=' . $this->accountUrlEnvBackup);
    }
}
