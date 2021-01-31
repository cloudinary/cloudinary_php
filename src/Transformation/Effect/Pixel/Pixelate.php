<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation;

/**
 * Class Pixelate
 */
class Pixelate extends SquareSizeEffectAction
{
    const REGION = PixelEffect::PIXELATE_REGION;
    const FACES  = PixelEffect::PIXELATE_FACES;

    /**
     * Pixelate constructor.
     *
     * @param       $squareSize
     * @param mixed ...$args
     */
    public function __construct($squareSize, ...$args)
    {
        parent::__construct(
            new SquareSizeEffectQualifier(PixelEffect::PIXELATE, EffectRange::PIXEL_REGION, $squareSize),
            ...$args
        );
    }

    use RegionEffectTrait;
}
