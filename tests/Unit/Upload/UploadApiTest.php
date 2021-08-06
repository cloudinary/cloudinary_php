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
use Cloudinary\Test\Helpers\MockUploadApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Unit\Asset\AssetTestCase;

/**
 * Class UploadApiTest
 */
final class UploadApiTest extends AssetTestCase
{
    use RequestAssertionsTrait;

    /**
     * Should support accessibility analysis in upload.
     *
     * @throws ApiError
     */
    public function testAccessibilityAnalysisUpload()
    {
        $mockUploadApi = new MockUploadApi();
        $mockUploadApi->upload(self::TEST_BASE64_IMAGE, ['accessibility_analysis' => true]);
        $lastOptions = $mockUploadApi->getApiClient()->getRequestMultipartOptions();

        self::assertArraySubset(['accessibility_analysis' => '1'], $lastOptions);
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

        self::assertContains('asset_id', $url);
        self::assertContains('version_id', $url);
    }
}
