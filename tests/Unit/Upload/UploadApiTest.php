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
use Cloudinary\Configuration\ApiConfig;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Helpers\MockUploadApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;

/**
 * Class UploadApiTest
 */
final class UploadApiTest extends AssetTestCase
{
    const TEST_CHUNK_SIZE = 7357;

    use RequestAssertionsTrait;

    /**
     * Should support accessibility analysis in upload.
     *
     * @throws ApiError
     */
    public function testVariousUploadParams()
    {
        $params = [
            'accessibility_analysis' => true,
            'cinemagraph_analysis'   => true,
            'media_metadata'         => true,
            'visual_search'          => true,
            'on_success'             => IntegrationTestCase::TEST_ON_SUCCESS_STR,
        ];

        $mockUploadApi = new MockUploadApi();
        $mockUploadApi->upload(
            self::TEST_BASE64_IMAGE,
            $params
        );

        $lastOptions = $mockUploadApi->getApiClient()->getRequestMultipartOptions();

        foreach ($params as $param => $value) {
            self::assertEquals(is_bool($value) ? $value ? '1' : '0': $value, $lastOptions[$param]);
        }
    }

    /**
     * Should support accessibility analysis in explicit.
     */
    public function testAccessibilityAnalysisExplicit()
    {
        $mockUploadApi = new MockUploadApi();
        $mockUploadApi->explicit(self::ASSET_ID, ['accessibility_analysis' => true]);
        $lastRequest = $mockUploadApi->getMockHandler()->getLastRequest();

        self::assertRequestBodySubset($lastRequest, ['accessibility_analysis' => '1']);
    }

    /**
     * Generate a url with asset and version id
     */
    public function testDownloadBackedupAsset()
    {
        $url = (new MockUploadApi())->downloadBackedupAsset(
            'b71b23d9c89a81a254b88a91a9dad8cd',
            '0e493356d8a40b856c4863c026891a4e'
        );

        self::assertStringContainsString('asset_id', $url);
        self::assertStringContainsString('version_id', $url);
    }

    /**
     * Should use default chunk size.
     *
     * @throws ApiError
     */
    public function testUploadDefaultChunkSize()
    {
        $mockUploadApi = new MockUploadApi();
        $mockUploadApi->upload(self::TEST_BASE64_IMAGE);
        $lastOptions = $mockUploadApi->getApiClient()->getRequestOptions();

        self::assertSame(ApiConfig::DEFAULT_CHUNK_SIZE, $lastOptions['chunk_size']);
    }

    /**
     * Should support setting custom chunk size.
     *
     * @throws ApiError
     */
    public function testUploadCustomChunkSizeOptions()
    {
        $mockUploadApi = new MockUploadApi();
        $mockUploadApi->upload(self::TEST_BASE64_IMAGE, ['chunk_size' => self::TEST_CHUNK_SIZE]);
        $lastOptions = $mockUploadApi->getApiClient()->getRequestOptions();

        self::assertSame(self::TEST_CHUNK_SIZE, $lastOptions['chunk_size']);
    }

    /**
     * Should support setting custom chunk size in config.
     *
     * @throws ApiError
     */
    public function testUploadCustomChunkSizeConfig()
    {
        Configuration::instance()->api->chunkSize = self::TEST_CHUNK_SIZE;

        $mockUploadApi = new MockUploadApi();
        $mockUploadApi->upload(self::TEST_BASE64_IMAGE);
        $lastOptions = $mockUploadApi->getApiClient()->getRequestOptions();

        self::assertSame(self::TEST_CHUNK_SIZE, $lastOptions['chunk_size']);
    }

    /**
     * Should pass folder decoupling params.
     *
     * @throws ApiError
     */
    public function testUploadFolderDecoupling()
    {
        $options = [
            'public_id_prefix'                     => self::FD_PID_PREFIX,
            'asset_folder'                         => self::ASSET_FOLDER,
            'use_asset_folder_as_public_id_prefix' => true,
            'display_name'                         => self::ASSET_DISPLAY_NAME,
            'use_filename_as_display_name'         => true,
            'folder'                               => self::NESTED_FOLDER,
        ];

        $mockUploadApi = new MockUploadApi();
        $mockUploadApi->upload(self::TEST_BASE64_IMAGE, $options);
        $lastOptions = $mockUploadApi->getApiClient()->getRequestMultipartOptions();

        self::assertSubset($options, $lastOptions);
    }

    /**
     * @dataProvider headersDataProvider
     */
    public function testHeadersExtraHeaders($input, $expectedOutput)
    {
        $mockUploadApi = new MockUploadApi();
        $mockUploadApi->upload(self::TEST_BASE64_IMAGE, $input);
        $mockOutput = $mockUploadApi->getApiClient()->getLastRequestHeaders();
        self::assertSubset($expectedOutput, $mockOutput);
    }

    /**
     * @return array
     */
    public function headersDataProvider()
    {
        return [
            [
                [
                    'headers'       => ['Content-Type' => 'application/json', 'Accept' => 'application/json'],
                    'extra_headers' => ['test1' => 'Bearer abc123', 'test2' => 'MyApp/1.0'],
                ],
                [
                    'Content-Type' => ['application/json'],
                    'Accept'       => ['application/json'],
                    'test1'        => ['Bearer abc123'],
                    'test2'        => ['MyApp/1.0'],
                ],
            ],
            [
                [
                    'headers'       => ['X-Request-ID' => '12345'],
                    'extra_headers' => ['Accept-Encoding' => 'gzip'],
                ],
                ['X-Request-ID' => ['12345'], 'Accept-Encoding' => ['gzip']],
            ],
            [
                [
                    'headers'       => ['Content-Language' => 'en-US'],
                    'extra_headers' => [],
                ],
                ['Content-Language' => ['en-US']],
            ],
            [
                [
                    'headers'       => [],
                    'extra_headers' => ['X-Debug' => ['true']],
                ],
                ['X-Debug' => ['true']],
            ],
            [
                [
                    'headers'       => [],
                    'extra_headers' => [],
                ],
                [],
            ],
        ];
    }
}
