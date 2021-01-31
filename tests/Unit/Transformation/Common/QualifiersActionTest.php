<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Common;

use Cloudinary\Test\Unit\UnitTestCase;
use Cloudinary\Transformation\QualifiersAction;
use Cloudinary\Transformation\Transformation;
use PHPUnit\Framework\TestCase;

/**
 * Class QualifiersActionTest
 */
final class QualifiersActionTest extends UnitTestCase
{
    public function testQualifiersAction()
    {
        self::assertEquals(
            'c_crop,h_20,w_10',
            new QualifiersAction(['crop' => 'crop', 'width' => 10, 'height' => 20])
        );

        self::assertStrEquals(
            'vc_h264:basic:3.1',
            Transformation::fromParams(['video_codec' => ['codec' => 'h264', 'profile' => 'basic', 'level' => '3.1']])
        );
    }
}
