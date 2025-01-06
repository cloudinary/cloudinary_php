<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Helpers;

use Cloudinary\Asset\Analytics;

/**
 * Class MockAnalytics
 */
class MockAnalytics extends Analytics
{
    protected static string $sdkVersion  = "2.3.4";
    protected static string $techVersion = '8.0';

    /**
     * Gets the SDK signature by encoding the SDK version and tech version.
     *
     */
    public static function sdkAnalyticsSignature(): string
    {
        self::$signature = null; // reset signature for tests

        return parent::sdkAnalyticsSignature();
    }
}
