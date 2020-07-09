<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Asset;

use Cloudinary\Asset\AssetTransformation;
use Cloudinary\Transformation\Format;
use Cloudinary\Transformation\Resize;
use Cloudinary\Transformation\Transformation;
use PHPUnit\Framework\TestCase;

/**
 * Class AssetTransformationTest
 */
final class AssetTransformationTest extends TestCase
{
    protected static $t;

    protected static $expectedTStr = 'c_fill,h_400,w_300';

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$t = (new Transformation())->resize(Resize::fill(300, 400));
    }

    public function testAssetTransformation()
    {
        $this->assertEquals(
            self::$expectedTStr,
            (string)(new AssetTransformation(self::$t))
        );
    }

    public function testAssetTransformationWithExt()
    {
        $this->assertEquals(
            self::$expectedTStr . '/' . Format::JPG,
            (string)(new AssetTransformation(self::$t, Format::JPG))
        );
    }

    public function testAssetTransformationWithEmptyExt()
    {
        $this->assertEquals(
            self::$expectedTStr . '/',
            (string)(new AssetTransformation(self::$t, ''))
        );
    }

    public function testAssetTransformationFromParams()
    {
        $params = [
            'crop'   => 'fill',
            'width'  => 300,
            'height' => 400,
        ];

        $this->assertEquals(
            self::$expectedTStr,
            (string)(new AssetTransformation($params))
        );

        $params['format'] = Format::JPG;

        $this->assertEquals(
            self::$expectedTStr . '/' . Format::JPG,
            (string)(new AssetTransformation($params))
        );

        $this->assertEquals(
            self::$expectedTStr . '/' . Format::JPG,
            (string)AssetTransformation::fromParams($params)
        );

        $params['format'] = '';

        $this->assertEquals(
            self::$expectedTStr . '/',
            (string)(new AssetTransformation($params))
        );
    }
}
