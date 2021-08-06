<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Test\Unit\Tag;

use Cloudinary\Asset\Image;
use Cloudinary\Tag\PictureSourceTag;
use Cloudinary\Tag\PictureTag;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Fill;
use Cloudinary\Transformation\Scale;

/**
 * Class PictureTagTest
 */
final class PictureTagTest extends ImageTagTestCase
{
    public function testPictureTag()
    {
        $this->src->resize(Fill::fill(self::MAX_WIDTH, self::MAX_WIDTH));

        $i1 = (new Image($this->src))
            ->effect(Effect::sepia())
            ->rotate(17)
            ->resize(Scale::scale(self::MIN_WIDTH));

        $i2 = (new Image($this->src))
            ->effect(Effect::colorize())
            ->rotate(18)
            ->resize(Scale::scale(self::MAX_WIDTH));

        $i3 = (new Image($this->src))
            ->effect(Effect::blur())
            ->rotate(19)
            ->resize(Scale::scale(self::MAX_WIDTH));

        $expectedSource1 = self::expectedSourceTag(
            $i1,
            null,
            null,
            null,
            null,
            ['media' => '(max-width: ' . self::MIN_WIDTH . 'px)']
        );

        $expectedSource2 = self::expectedSourceTag(
            $i2,
            null,
            null,
            null,
            null,
            ['media' => '(min-width: ' . self::MIN_WIDTH . 'px) and (max-width: ' . self::MAX_WIDTH . 'px)']
        );

        $expectedSource3 = self::expectedSourceTag(
            $i3,
            null,
            null,
            null,
            null,
            ['media' => '(min-width: ' . self::MAX_WIDTH . 'px)']
        );

        $expectedImage = self::expectedImageTag(
            $this->src->getPublicId(),
            self::getAssetHostNameAndType($this->src),
            $this->src->getTransformation()
        );

        $expected = implode(
            "\n",
            ['<picture>', $expectedSource1, $expectedSource2, $expectedSource3, $expectedImage, '</picture>']
        );

        $tag = new PictureTag(
            $this->src,
            [
                new PictureSourceTag($i1, null, self::MIN_WIDTH),
                new PictureSourceTag($i2, self::MIN_WIDTH, self::MAX_WIDTH),
                new PictureSourceTag($i3, self::MAX_WIDTH)
            ]
        );

        self::assertStrEquals(
            $expected,
            $tag
        );
    }
}
