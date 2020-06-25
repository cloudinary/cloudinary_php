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

use InvalidArgumentException;

/**
 * Enhances an image to its best visual quality with the Viesus Automatic Image Enhancement add-on.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/viesus_automatic_image_enhancement_addon" target="_blank">
 * Viesus Automatic Image Enhancement</a>.
 *
 * @api
 *
 * @see https://cloudinary.com/documentation/viesus_automatic_image_enhancement_addon
 */
class ViesusCorrect extends LimitedEffectParam
{
    use EffectModeTrait;

    /**
     * Enhances the image without correcting for red eye.
     */
    const NO_REDEYE = 'no_redeye';

    /**
     * Enhances the image and also applies saturation to the skin tones in the image.
     */
    const SKIN_SATURATION = 'skin_saturation';

    /**
     * ViesusCorrect constructor.
     *
     * @param string $mode  The enhancement mode. Use the constants defined in this class.
     * @param int    $level The enhancement level. (Range: -100 to 100, Server default: 50).
     */
    public function __construct($mode = null, $level = null)
    {
        if ($level !== null && $mode !== self::SKIN_SATURATION) {
            throw new InvalidArgumentException('Level can be set only for SKIN_SATURATION mode');
        }

        parent::__construct(Adjust::VIESUS_CORRECT, EffectRange::DEFAULT_RANGE, $level);

        $this->mode($mode);
    }
}
