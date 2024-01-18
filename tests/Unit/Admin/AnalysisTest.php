<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Admin;

use Cloudinary\Api\Exception\ApiError;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Test\Helpers\MockAdminApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Integration\IntegrationTestCase;
use Cloudinary\Test\Unit\Asset\AssetTestCase;
use Cloudinary\Test\Unit\UnitTestCase;

/**
 * Class AnalysisTest
 */
final class AnalysisTest extends UnitTestCase
{
    use RequestAssertionsTrait;

    /**
     * Test analyze.
     */
    public function testAnalyze()
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->analyze("uri", "captioning", "https://res.cloudinary.com/demo/image/upload/dog");

        $lastRequest = $mockAdminApi->getV2MockHandler()->getLastRequest();
        $apiV2Configuration = new Configuration();
        $apiV2Configuration->api->apiVersion = '2';

        self::assertRequestUrl($lastRequest, '/analysis/analyze', "", $apiV2Configuration);
        self::assertRequestJsonBodySubset(
            $lastRequest,
            [
                "input_type"=> "uri",
                "analysis_type"=> "captioning",
                "uri"=> "https://res.cloudinary.com/demo/image/upload/dog",
            ]
        );
    }
}
