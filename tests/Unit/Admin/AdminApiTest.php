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

use Cloudinary\Test\Helpers\MockAdminApi;
use Cloudinary\Test\Helpers\RequestAssertionsTrait;
use Cloudinary\Test\Unit\UnitTestCase;

/**
 * Class AdminApiTest
 */
final class AdminApiTest extends UnitTestCase
{
    use RequestAssertionsTrait;

    /**
     * Should allow the user to pass accessibility_analysis in the asset function.
     */
    public function testAccessibilityAnalysisResource()
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->asset(self::$UNIQUE_TEST_ID, ['accessibility_analysis' => true]);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestQueryStringSubset($lastRequest, ['accessibility_analysis' => '1']);
    }

    /**
     * Should allow the user to pass accessibility_analysis in the createUploadPreset function.
     */
    public function testAccessibilityAnalysisUploadPreset()
    {
        $mockAdminApi = new MockAdminApi();
        $mockAdminApi->createUploadPreset(['accessibility_analysis' => true]);
        $lastRequest = $mockAdminApi->getMockHandler()->getLastRequest();

        self::assertRequestBodySubset($lastRequest, ['accessibility_analysis' => '1']);
    }
}
