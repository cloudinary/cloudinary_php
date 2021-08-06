<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Transformation\Video;

use Cloudinary\Transformation\AnimatedFormat;
use Cloudinary\Transformation\Transcode;
use PHPUnit\Framework\TestCase;

/**
 * Class VideoTranscodeTest
 */
final class VideoTranscodeTest extends TestCase
{
    public function testTranscodeVideo()
    {
        self::assertEquals(
            'f_auto,fl_animated',
            (string)Transcode::toAnimated('auto')
        );

        self::assertEquals(
            'f_gif,fl_animated',
            (string)Transcode::toAnimated(AnimatedFormat::gif())
        );

        self::assertEquals(
            'f_webp,fl_animated,fl_awebp,vs_10',
            (string)Transcode::toAnimated(AnimatedFormat::webp())->sampling(10)
        );
    }
}
