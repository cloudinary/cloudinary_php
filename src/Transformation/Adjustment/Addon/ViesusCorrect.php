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
class ViesusCorrect extends EffectQualifier
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
     */
    public function __construct()
    {
        parent::__construct(Adjust::VIESUS_CORRECT);
    }

    /**
     * Enhances the image without correcting for red eye.
     *
     * @return ViesusCorrect
     */
    public function noRedEye()
    {
        $this->getValue()->setSimpleNamedValue('no', 'redeye');

        return $this;
    }

    /**
     * Enhances the image and also applies saturation to the skin tones in the image.
     *
     * @param int $level    The enhancement level. A positive value boosts the saturation and a negative value
     *                      reduces the saturation. (Range: -100 to 100, Server default: 50).
     *
     * @return ViesusCorrect
     */
    public function skinSaturation($level = null)
    {
        if ($level) {
            $this->getValue()->setSimpleNamedValue(self::SKIN_SATURATION, $level);
        } else {
            $this->getValue()->addValues(self::SKIN_SATURATION);
        }

        return $this;
    }
}
