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

use Cloudinary\Tag\PictureTag;
use Cloudinary\Transformation\Effect;
use Cloudinary\Transformation\Fill;
use Cloudinary\Transformation\ImageTransformation;
use Cloudinary\Transformation\Scale;

/**
 * Class PictureTagTest
 */
final class PictureTagTest extends ImageTagTestCase
{
    public function testPictureTag()
    {
        $this->src->resize(Fill::fill(self::MAX_WIDTH, self::MAX_WIDTH));

        $t1 = (new ImageTransformation())
            ->effect(Effect::sepia())
            ->rotate(17)
            ->resize(Scale::scale(self::MIN_WIDTH));

        $t2 = (new ImageTransformation())
            ->effect(Effect::colorize())
            ->rotate(18)
            ->resize(Scale::scale(self::MAX_WIDTH));

        $t3 = (new ImageTransformation())
            ->effect(Effect::blur())
            ->rotate(19)
            ->resize(Scale::scale(self::MAX_WIDTH));

        $tag = new PictureTag(
            $this->src,
            [
                [
                    'max_width'      => self::MIN_WIDTH,
                    'transformation' => $t1,
                ],
                [
                    'min_width'      => self::MIN_WIDTH,
                    'max_width'      => self::MAX_WIDTH,
                    'transformation' => $t2,

                ],
                [
                    'min_width'      => self::MAX_WIDTH,
                    'transformation' => $t3,
                ],
            ]
        );

        $expectedSource1 = self::expectedSourceTag(
            $this->src->getPublicId(),
            self::getAssetHostNameAndType($this->src),
            $this->src->getTransformation()->toUrl($t1),
            null,
            null,
            ['media' => '(max-width: ' . self::MIN_WIDTH . 'px)']
        );

        $expectedSource2 = self::expectedSourceTag(
            $this->src->getPublicId(),
            self::getAssetHostNameAndType($this->src),
            $this->src->getTransformation()->toUrl($t2),
            null,
            null,
            ['media' => '(min-width: ' . self::MIN_WIDTH . 'px) and (max-width: ' . self::MAX_WIDTH . 'px)']
        );

        $expectedSource3 = self::expectedSourceTag(
            $this->src->getPublicId(),
            self::getAssetHostNameAndType($this->src),
            $this->src->getTransformation()->toUrl($t3),
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

        $this->assertEquals(
            (string)$expected,
            (string)$tag
        );
    }
}
