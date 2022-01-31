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

use Cloudinary\ArrayUtils;
use Cloudinary\ClassUtils;

/**
 * Adds a solid border around an image or video.
 *
 * **Learn more**:
 * <a href="https://cloudinary.com/documentation/image_transformations#adding_image_borders" target="_blank">
 * Adding image borders</a>
 *
 * @api
 */
class Border extends BaseAction
{
    const MAIN_QUALIFIER = BorderQualifier::class;

    use BorderStyleTrait;

    /**
     * Sets the width of the border.
     *
     * @param int|string $width The width in pixels.
     *
     * @return $this
     */
    public function width($width)
    {
        $this->getMainQualifier()->width($width);

        return $this;
    }

    /**
     * Sets the style of the border.
     *
     * @param string $style The style of the border.  Currently only "solid" is supported.
     *
     * @return $this
     */
    public function style($style)
    {
        $this->getMainQualifier()->style($style);

        return $this;
    }

    /**
     * Sets the color of the border.
     *
     * @param string $color The color of the border.
     *
     * @return $this
     */
    public function color($color)
    {
        $this->getMainQualifier()->color($color);

        return $this;
    }

    /**
     * Rounds the specified corners of an image.
     *
     * Only $radiusOrTopLeft specified: All four corners are rounded equally according to the value.<br>
     * Only $radiusOrTopLeft and $topRight specified: Round the top-left & bottom-right corners according
     * to $radiusOrTopLeft, round the top-right & bottom-left corners according to $topRight.<br>
     * Only $radiusOrTopLeft, $topRight and $bottomRight specified: Round the top-left corner according
     * to $radiusOrTopLeft, round the top-right & bottom-left corners according to $topRight, round the bottom-right
     * corner according to $bottomRight.<br>
     * All qualifiers specified: Each corner is rounded accordingly.
     *
     * @param int|string|CornerRadius $radiusOrTopLeft The radius in pixels of the top left corner or all the corners
     *                                                 if no other corners are specified.
     * @param int                     $topRight        The radius in pixels of the top right corner.
     * @param int                     $bottomRight     The radius in pixels of the bottom right corner.
     * @param int                     $bottomLeft      The radius in pixels of the bottom left corner.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\RoundCorners
     */
    public function roundCorners($radiusOrTopLeft, $topRight = null, $bottomRight = null, $bottomLeft = null)
    {
        $qualifiers = ArrayUtils::safeFilter([$radiusOrTopLeft, $topRight, $bottomRight, $bottomLeft]);

        return $this->addQualifier(ClassUtils::verifyVarArgsInstance($qualifiers, RoundCorners::class));
    }
}
