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
 * Defines the mode of blending to use when overlaying an image.
 *
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/image_transformations#overlay_blending_effects" target="_blank">
 * Overlay blending effects</a>
 *
 * @api
 */
class BlendMode extends EffectQualifier
{
    /**
     * @var string $name Serialization name.
     */
    protected static $name = 'blend_mode';

    /**
     * Each pixel of the image is made darker according to the pixel value of the overlaid image.
     */
    const MULTIPLY     = 'multiply';

    /**
     * Each pixel of the image is made brighter according to the pixel value of the overlaid image.
     */
    const SCREEN       = 'screen';

    /**
     * Each pixel of the image is made darker or brighter according to the pixel value of the overlaid image.
     */
    const OVERLAY      = 'overlay';

    /**
     * Each pixel of the image is 'cut-out' according to the non-transparent pixels of the overlaid
     * image.
     */
    const MASK         = 'mask';

    /**
     * The overlay is slightly distorted to prevent easy removal.
     */
    const ANTI_REMOVAL = 'anti_removal';

    /**
     * Add an overlay image blended using the 'multiply' blend mode.
     *
     * In this mode, each pixel of the image is made darker according to the pixel value of the overlaid image.
     *
     * @return BlendMode
     */
    public static function multiply()
    {
        return new self(self::MULTIPLY);
    }

    /**
     * Add an overlay image blended using the 'screen' blend mode.
     *
     * In this mode, each pixel of the image is made brighter according to the pixel value of the overlaid image.
     *
     * @return BlendMode
     */
    public static function screen()
    {
        return new self(self::SCREEN);
    }

    /**
     * Add an overlay image blended using the 'overlay' blend mode.
     *
     * In this mode, each pixel of the image is made darker or brighter according to the pixel value of the overlaid
     * image.
     *
     * @return BlendMode
     */
    public static function overlay()
    {
        return new self(self::OVERLAY);
    }

    /**
     * Add an overlay image blended using the 'mask' blend mode.
     *
     * In this mode, each pixel of the image is 'cut-out' according to the non-transparent pixels of the overlaid
     * image.
     *
     * @return BlendMode
     */
    public static function mask()
    {
        return new self(self::MASK);
    }

    /**
     * Add an overlay image blended using the 'anti-removal' blend mode.
     *
     * In this mode, the overlay is slightly distorted to prevent easy removal.
     *
     * @param int $level The level of distortion. (Range: 1 to 100, Server default: 50)
     *
     * @return BlendMode
     */
    public static function antiRemoval($level = null)
    {
        return new self(self::ANTI_REMOVAL, $level);
    }
}
