<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Upload;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Helpers\MockUploadApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use InvalidArgumentException;

/**
 * Class OAuthTest
 */
final class OAuthTest extends AssetTestCase
{
    use RequestAssertionsTrait;

    const FAKE_OAUTH_TOKEN = 'MTQ0NjJkZmQ5OTM2NDE1ZTZjNGZmZjI4';
    const API_TEST_PRESET = 'api_test_upload_preset';

    /**
     * Should upload an asset using an Oauth Token.
     *
     * @throws ApiError
     */
    public function testOauthTokenUploadApi()
    {
        $config = new Configuration(Configuration::instance());
        $config->cloud->oauthToken(self::FAKE_OAUTH_TOKEN);

        $uploadApi = new MockUploadApi($config);
        $uploadApi->upload(self::TEST_BASE64_IMAGE);
        $lastRequest = $uploadApi->getMockHandler()->getLastRequest();

        self::assertRequestHeaderSubset(
            $lastRequest,
            [
                'Authorization' => ['Bearer ' . self::FAKE_OAUTH_TOKEN]
            ]
        );
    }

    /**
     * Should upload an asset using `apiKey` and `apiSecret` if an Oauth Token is absent.
     *
     * @throws ApiError
     */
    public function testKeyAndSecretUploadApi()
    {
        $config = new Configuration(Configuration::instance());
        $config->cloud->oauthToken(null);

        $uploadApi = new MockUploadApi($config);
        $uploadApi->upload(self::TEST_BASE64_IMAGE);

        $params = $uploadApi->getApiClient()->getRequestMultipartOptions();

        self::assertArrayHasKey('api_key', $params);
        self::assertArrayHasKey('signature', $params);
    }

    /**
     * Should be thrown an exception if `apiKey` and `apiSecret` or an Oauth Token are absent.
     *
     * @throws ApiError
     */
    public function testMissingCredentialsUploadApi()
    {
        $config = new Configuration(Configuration::instance());
        $config->cloud->oauthToken(null);
        $config->cloud->apiKey = null;
        $config->cloud->apiSecret = null;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Must supply apiKey');

        $uploadApi = new MockUploadApi($config);
        $uploadApi->upload(self::TEST_BASE64_IMAGE);
    }

    /**
     * Should upload an asset using an upload preset if `apiKey` and `apiSecret` or an Oauth Token are absent.
     *
     * @throws ApiError
     */
    public function testMissingCredentialsUnsignedUploadApi()
    {
        $config = new Configuration(Configuration::instance());
        $config->cloud->oauthToken(null);
        $config->cloud->apiKey = null;
        $config->cloud->apiSecret = null;

        $uploadApi = new MockUploadApi($config);
        $uploadApi->unsignedUpload(self::TEST_BASE64_IMAGE, self::API_TEST_PRESET);

        self::assertSame(
            self::API_TEST_PRESET,
            $uploadApi->getApiClient()->getRequestMultipartOptions()['upload_preset']
        );
    }
}
