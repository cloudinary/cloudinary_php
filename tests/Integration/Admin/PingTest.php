<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Integration\Admin;

use Cloudinary\Test\Integration\IntegrationTestCase;

/**
 * Class PingTest
 */
final class PingTest extends IntegrationTestCase
{
    public function testPing()
    {
        $result = self::$adminApi->ping();

        self::assertEquals('ok', $result['status']);
    }

    public function testPingAsync()
    {
        $result = self::$adminApi->pingAsync()->wait();

        self::assertEquals('ok', $result['status']);
    }
}
