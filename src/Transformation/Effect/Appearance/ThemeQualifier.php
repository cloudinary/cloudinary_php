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

use Cloudinary\ClassUtils;
use Cloudinary\StringUtils;
use Cloudinary\Transformation\Argument\ColorValue;

/**
 * Changes the main background color to the one specified, as if a 'theme change' was applied
 * (e.g. dark mode vs light mode).
 *
 * @see https://cloudinary.com/documentation/transformation_reference#e_theme
 */
class ThemeQualifier extends EffectQualifier
{
    const NAME = 'theme';

    protected $valueOrder = [0, 'color', 'photosensitivity'];

    public function __construct($color, $photoSensitivity)
    {
        parent::__construct(self::NAME);

        $this->color($color);
        $this->photoSensitivity($photoSensitivity);
    }

    /**
     * Sets the theme color.
     *
     * @param string $color The target background color. Specify either the name of a color (e.g. black, lightgray),
     *                      or an RGB hex value (e.g. f0ebe6).
     *
     * @return ThemeQualifier
     */
    public function color($color)
    {
        $color = StringUtils::truncatePrefix((string)$color, '#');
        $this->value->setSimpleNamedValue('color', ClassUtils::verifyInstance($color, ColorValue::class));

        return $this;
    }

    /**
     * Sets the theme photosensitivity.
     *
     * @param int $photoSensitivity The sensitivity to photographic elements of an image. A value of 0 treats the whole
     *                              image as non-photographic. A value of 200 treats the whole image as photographic,
     *                              so no theme change is applied. Range: 0 to 200. Default: 100.
     *
     * @return ThemeQualifier
     */
    public function photoSensitivity($photoSensitivity)
    {
        $this->value->setSimpleNamedValue('photosensitivity', $photoSensitivity);

        return $this;
    }
}
