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

use Cloudinary\Asset\Media;

/**
 * Trims pixels according to the transparency levels of a specified overlay image.
 *
 * Wherever an overlay image is transparent, the original is shown, and wherever an overlay is opaque, the resulting
 * image is transparent.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/cookbook/custom_shapes_cut_out" target="_blank">
 * Custom shapes cut out</a>
 *
 * @api
 */
class CutOut extends SourceBasedEffectAction
{
    /**
     * CutOut constructor.
     *
     * @param string|Media $source The public ID of the source.
     */
    public function __construct($source)
    {
        parent::__construct(new EffectQualifier(PixelEffect::CUT_OUT));

        $this->source($source);
    }
}
