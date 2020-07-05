<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Api\Provisioning;

use Cloudinary\Api\BaseApiClient;
use Cloudinary\Configuration\Provisioning\ProvisioningConfiguration;
use Cloudinary\Exception\ConfigurationException;
use GuzzleHttp\Client;

/**
 * Class AccountApiClient
 *
 * @package Cloudinary\Api
 *
 * @internal
 */
class AccountApiClient extends BaseApiClient
{
    const PROVISIONING = 'provisioning';
    const ACCOUNTS     = 'accounts';

    /**
     * AccountApiClient constructor
     *
     * @param ProvisioningConfiguration $configuration
     */
    public function __construct(ProvisioningConfiguration $configuration = null)
    {
        $this->init($configuration);
    }

    /**
     * @param ProvisioningConfiguration $configuration
     */
    public function init(ProvisioningConfiguration $configuration = null)
    {
        if ($configuration === null) {
            $configuration = ProvisioningConfiguration::instance();
        }

        if (empty($configuration->provisioningAccount->accountId)
            || empty($configuration->provisioningAccount->provisioningApiKey)
            || empty($configuration->provisioningAccount->provisioningApiSecret)) {
            throw new ConfigurationException(
                'When providing account id, key or secret, all must be provided'
            );
        }

        $this->api     = $configuration->api;
        $this->logging = $configuration->logging;

        $this->baseUri = sprintf(
            '%s/%s/%s/%s/%s/',
            $this->api->uploadPrefix,
            self::apiVersion(),
            self::PROVISIONING,
            self::ACCOUNTS,
            $configuration->provisioningAccount->accountId
        );

        $clientConfig = [
            'auth'            => [
                $configuration->provisioningAccount->provisioningApiKey,
                $configuration->provisioningAccount->provisioningApiSecret,
            ],
            'base_uri'        => $this->baseUri,
            'connect_timeout' => $this->api->connectionTimeout,
            'timeout'         => $this->api->timeout,
            'proxy'           => $this->api->apiProxy,
            'headers'         => ['User-Agent' => self::userAgent()],
            'http_errors'     => false, // We handle HTTP errors by ourselves and throw corresponding exceptions
        ];

        $this->httpClient = new Client($clientConfig);
    }
}
