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

use Cloudinary\Transformation\Flag;
use Cloudinary\Transformation\ImageFlag;
use Cloudinary\Transformation\JpegScanMode;
use Cloudinary\Transformation\VideoFlag;
use PHPUnit\Framework\TestCase;

/**
 * Class FlagTest
 */
final class FlagTest extends TestCase
{
    public function testVariousFlags()
    {
        $this->assertEquals(
            'fl_attachment:dummy',
            (string)Flag::attachment('dummy')
        );

        $this->assertEquals(
            'fl_truncate_ts',
            (string)VideoFlag::truncateTS()
        );

        $this->assertEquals(
            'fl_progressive:steep',
            (string)ImageFlag::progressive(JpegScanMode::STEEP)
        );

        $this->assertEquals(
            'fl_my_flag:17',
            (string)Flag::generic('my_flag', 17)
        );
    }
}
