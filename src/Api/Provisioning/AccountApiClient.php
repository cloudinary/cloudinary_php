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
use Cloudinary\Configuration\Provisioning\ProvisioningAccountConfig;
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
    public const PROVISIONING = 'provisioning';
    public const ACCOUNTS     = 'accounts';

    /**
     * @var ProvisioningAccountConfig $provisioningAccount The Account API configuration.
     */
    protected ProvisioningAccountConfig $provisioningAccount;

    /**
     * AccountApiClient constructor
     *
     */
    public function __construct(?ProvisioningConfiguration $configuration = null)
    {
        $this->init($configuration);
    }

    public function init(?ProvisioningConfiguration $configuration = null): void
    {
        if ($configuration === null) {
            $configuration = ProvisioningConfiguration::instance();
        }

        if (empty($configuration->provisioningAccount->accountId)
            || empty($configuration->provisioningAccount->provisioningApiKey)
            || empty($configuration->provisioningAccount->provisioningApiSecret)
        ) {
            throw new ConfigurationException(
                'When providing account id, key or secret, all must be provided'
            );
        }

        $this->api                 = $configuration->api;
        $this->provisioningAccount = $configuration->provisioningAccount;
        $this->logging             = $configuration->logging;

        $this->baseUri = sprintf(
            '%s/%s/%s/%s/%s/',
            $this->api->uploadPrefix,
            self::apiVersion($this->api->apiVersion),
            self::PROVISIONING,
            self::ACCOUNTS,
            $configuration->provisioningAccount->accountId
        );

        $this->createHttpClient();
    }

    protected function createHttpClient(): void
    {
        $this->httpClient = new Client($this->buildHttpClientConfig());
    }

    protected function buildHttpClientConfig(): array
    {
        return [
            'auth'            => [
                $this->provisioningAccount->provisioningApiKey,
                $this->provisioningAccount->provisioningApiSecret,
            ],
            'base_uri'        => $this->baseUri,
            'connect_timeout' => $this->api->connectionTimeout,
            'timeout'         => $this->api->timeout,
            'proxy'           => $this->api->apiProxy,
            'headers'         => ['User-Agent' => self::userAgent()],
            'http_errors'     => false, // We handle HTTP errors by ourselves and throw corresponding exceptions
        ];
    }


}
