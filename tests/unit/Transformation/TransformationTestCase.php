<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit;

/**
 * Class CloudinaryTestCase
 */
abstract class TransformationTestCase extends UnitTestCase
{
    /**
     * @param        $expectedStr
     * @param        $component
     * @param string $messages
     */
    protected function assertComponentEquals($expectedStr, $component, $messages = '')
    {
        $this->assertEquals($expectedStr, (string)$component, $messages);
    }
}
