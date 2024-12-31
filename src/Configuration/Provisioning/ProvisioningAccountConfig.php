<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Configuration\Provisioning;

use Cloudinary\Configuration\BaseConfigSection;

/**
 * Class ProvisioningAccountConfig
 *
 * @api
 */
class ProvisioningAccountConfig extends BaseConfigSection
{
    public const CONFIG_NAME = 'provisioning_account';

    // Supported parameters
    public const ACCOUNT_ID = 'account_id';
    public const PROVISIONING_API_KEY = 'provisioning_api_key';
    public const PROVISIONING_API_SECRET = 'provisioning_api_secret';

    /**
     * The account id of your Cloudinary account. Mandatory for provisioning API operations.
     *
     * @var string
     */
    public string $accountId;

    /**
     * The provisioning API key. Mandatory for provisioning API operations.
     *
     * @var string
     */
    public string $provisioningApiKey;

    /**
     * The provisioning API secret. Mandatory for provisioning API operations.
     *
     * @var string
     */
    public string $provisioningApiSecret;
}
