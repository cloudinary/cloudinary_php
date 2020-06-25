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

use Cloudinary\Transformation\Argument\Gradient;

/**
 * Automatically sets the background color when resizing with padding.
 *
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/image_transformations#content_aware_padding" target="_blank">
 * Content-aware padding</a>
 *
 * @api
 */
class AutoBackground extends Background
{
    /**
     * Selects the predominant color while taking only the image border pixels into account. (Server default)
     */
    const BORDER = 'border';

    /**
     * Selects the predominant color while taking all pixels in the image into account.
     */
    const PREDOMINANT = 'predominant';

    /**
     * Selects the strongest contrasting color to the predominant color while taking only the image border pixels
     * into account.
     */
    const BORDER_CONTRAST = 'border_contrast';

    /**
     * Selects the strongest contrasting color to the predominant color while taking all pixels in the image into
     * account.
     */
    const PREDOMINANT_CONTRAST = 'predominant_contrast';

    /**
     * @var string $name The name.
     */
    protected static $name = 'background';

    /**
     * @var string $type The type of the background color. Use the constants defined in this class.
     */
    protected $type;

    /**
     * AutoBackground constructor.
     *
     * @param $type
     */
    public function __construct($type)
    {
        parent::__construct('auto');

        $this->type($type);
    }

    /**
     * Selects the predominant color while taking only the image border pixels into account.
     *
     * @return AutoBackground
     */
    public static function border()
    {
        return new AutoBackground(self::BORDER);
    }

    /**
     * Selects the predominant color while taking all pixels in the image into account.
     *
     * @return AutoBackground
     */
    public static function predominant()
    {
        return new AutoBackground(self::PREDOMINANT);
    }

    /**
     * Selects the strongest contrasting color to the predominant color while taking only the image border pixels
     * into account.
     *
     * @return AutoBackground
     */
    public static function borderContrast()
    {
        return new AutoBackground(self::BORDER_CONTRAST);
    }

    /**
     * Selects the strongest contrasting color to the predominant color while taking all pixels in the image into
     * account.
     *
     * @return AutoBackground
     */
    public static function predominantContrast()
    {
        return new AutoBackground(self::PREDOMINANT_CONTRAST);
    }

    /**
     * Applies a padding gradient fade effect with the predominant colors in the image.
     *
     * @param string $type           The type of gradient fade. Use the constants defined in the Gradient class.
     * @param int    $numberOfColors The number of predominant colors to use (2 or 4, Server default: 2).
     * @param string $direction      The direction of fade.  Use the constants defined in the GradientDirection class.
     *
     * @return AutoBackground
     *
     * @see \Cloudinary\Transformation\Argument\Gradient
     * @see \Cloudinary\Transformation\Argument\GradientDirection
     */
    public static function gradientFade($type = null, $numberOfColors = null, $direction = null)
    {
        return new AutoBackground(new Gradient($type, $numberOfColors, $direction));
    }

    /**
     * Determines which color is automatically chosen for the background.
     *
     * @param string $type Use the constants defined in this class.
     *
     * @return AutoBackground
     */
    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Serializes to string.
     *
     * @return string
     */
    public function __toString()
    {
        return implode(':', array_filter([parent::__toString(), $this->type]));
    }
}
