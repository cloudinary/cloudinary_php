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

use Cloudinary\Transformation\ParametersAction;
use PHPUnit\Framework\TestCase;

/**
 * Class ParametersActionTest
 */
final class ParametersActionTest extends TestCase
{
    protected static $cropTransArr = ['crop' => 'crop', 'width' => 10, 'height' => 20];
    protected static $cropTransStr = 'c_crop,h_20,w_10';

    public function testParametersAction()
    {
        $pc = new ParametersAction(self::$cropTransArr);

        $this->assertEquals(
            self::$cropTransStr,
            (string)$pc
        );

        $pc = new ParametersAction(['video_codec' => ['codec' => 'h264', 'profile' => 'basic', 'level' => '3.1']]);

        $this->assertEquals(
            'vc_h264:basic:3.1',
            (string)$pc
        );
    }
}
