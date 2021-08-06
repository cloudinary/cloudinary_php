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
use Cloudinary\Transformation\Argument\ColorValue;

/**
 * Trait ImageSpecificTransformationTrait
 *
 * @api
 */
trait ImageSpecificTransformationTrait
{
    use ImageTransformationFlagTrait;
    use ImageQualifierTransformationTrait;
    use LayeredImageTransformationTrait;

    /**
     * Adds an overlay over the base image.
     *
     * @param string                    $layer     The public ID of the image to overlay.
     * @param Position|AbsolutePosition $position  The position of the overlay with respect to the base image.
     * @param string                    $blendMode The blend mode. Use the constants defined in the BlendMode class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\ImageOverlay
     * @see \Cloudinary\Transformation\BlendMode
     */
    public function overlay($layer, $position = null, $blendMode = null)
    {
        return $this->addAction(
            ClassUtils::verifyInstance(
                $layer,
                BaseSourceContainer::class,
                ImageOverlay::class,
                $position,
                $blendMode
            )
        );
    }

    /**
     * Adds an underlay under the base image.
     *
     * @param string                    $layer     The public ID of the image to underlay.
     * @param Position|AbsolutePosition $position  The position of the underlay with respect to the base image.
     * @param string                    $blendMode The blend mode. Use the constants defined in the BlendMode class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\OverlayOverlay
     * @see \Cloudinary\Transformation\BlendMode
     */
    public function underlay($layer, $position = null, $blendMode = null)
    {
        $underlay = ClassUtils::forceInstance($layer, ImageOverlay::class, null, $position, $blendMode);
        $underlay->setStackPosition(LayerStackPosition::UNDERLAY);

        return $this->addAction($underlay);
    }

    /**
     * Changes the shape of the image.
     *
     * @param ReshapeQualifier|EffectAction|EffectQualifier|mixed $reshape The reshape to apply.
     *
     * @return static
     */
    public function reshape($reshape)
    {
        return $this->addAction($reshape);
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

        $roundCorners = ClassUtils::verifyVarArgsInstance($qualifiers, RoundCorners::class);

        return $this->addAction($roundCorners);
    }

    /**
     * Adds a border around the image.
     *
     * @param Border $border A Border object in which you set the width, style and color of the border.
     *                       See the Border class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Border
     */
    public function border(Border $border)
    {
        return $this->addAction($border);
    }

    /**
     * Sets the color of the background.
     *
     * @param Background|ColorValue|string $color The color of the background to set.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Background
     */
    public function backgroundColor($color)
    {
        return $this->addAction(ClassUtils::verifyInstance($color, Background::class));
    }

    /**
     * Sets the image background.
     *
     * @param Background|string $background The background to set.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Background
     */
    public function background($background)
    {
        return $this->addAction(ClassUtils::verifyInstance($background, Background::class));
    }

    /**
     * Controls the color space used for the delivered image.
     *
     * @param ColorSpace|string $colorSpace Use the constants defined in the ColorSpace class.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\ColorSpace
     */
    public function colorSpace($colorSpace)
    {
        return $this->addAction(ClassUtils::verifyInstance($colorSpace, ColorSpace::class));
    }

    /**
     * Prevents style class names collisions for sprite generation.
     *
     * @param Prefix|string $prefix The style class name prefix.
     *
     *
     * @return static
     */
    public function prefix($prefix)
    {
        return $this->addAction(ClassUtils::verifyInstance($prefix, Prefix::class));
    }

    /**
     * Applies animated image transformation action.
     *
     * @param AnimatedEdit $animated Animated image action.
     *
     * @return static
     */
    public function animated($animated)
    {
        return $this->addAction(ClassUtils::verifyInstance($animated, AnimatedEdit::class));
    }
}
