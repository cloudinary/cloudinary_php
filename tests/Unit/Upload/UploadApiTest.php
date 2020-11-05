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
use Cloudinary\Test\Integration\TestHelpers\MockUploadApi;
use Cloudinary\Test\Traits\RequestAssertionsTrait;
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
        $lastRequest = $mockUploadApi->getMockHandler()->getLastRequest();

        self::assertMultipartRequestHeadersSubset($lastRequest, ['accessibility_analysis' => '1']);
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
}
