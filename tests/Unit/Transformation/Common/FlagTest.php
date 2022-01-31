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
use Cloudinary\Transformation\Progressive;
use Cloudinary\Transformation\VideoFlag;
use PHPUnit\Framework\TestCase;

/**
 * Class FlagTest
 */
final class FlagTest extends TestCase
{
    public function testVariousFlags()
    {
        self::assertEquals(
            'fl_attachment:dummy',
            (string)Flag::attachment('dummy')
        );

        self::assertEquals(
            'fl_truncate_ts',
            (string)VideoFlag::truncateTS()
        );

        self::assertEquals(
            'fl_progressive:steep',
            (string)ImageFlag::progressive(Progressive::steep())
        );

        self::assertEquals(
            'fl_my_flag:17',
            (string)Flag::generic('my_flag', 17)
        );

        self::assertEquals(
            'fl_ignore_mask_channels',
            (string)Flag::ignoreMaskChannels()
        );
    }
}
