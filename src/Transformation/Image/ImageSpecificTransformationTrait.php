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
 * Trait ImageSpecificTransformationTrait
 *
 * @api
 */
trait ImageSpecificTransformationTrait
{
    use ImageTransformationFlagTrait;
    use ImageParameterTransformationTrait;
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
     * @see \Cloudinary\Transformation\Overlay
     * @see \Cloudinary\Transformation\BlendMode
     */
    public function overlay($layer, $position = null, $blendMode = null)
    {
        return $this->addAction(new Overlay($layer, $position, $blendMode));
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
        $layer = ClassUtils::forceInstance($layer, BaseLayer::class, ImageLayer::class);

        $layer->setStackPosition(LayerStackPosition::UNDERLAY);

        return $this->addAction(new Overlay($layer, $position, $blendMode));
    }

    /**
     * Trims pixels according to the transparency levels of a given overlay image.
     *
     * Wherever the overlay image is opaque, the original is shown, and wherever the overlay is transparent,
     * the result is transparent as well.
     *
     * @param string                    $layer    The public ID of the image overlay.
     * @param Position|AbsolutePosition $position The position of the overlay with respect to the base image.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Overlay
     */
    public function cutter($layer, $position = null)
    {
        return $this->addAction((new Overlay($layer, $position))->cutter());
    }

    /**
     * Changes the shape of the image.
     *
     * @param ReshapeParam|EffectAction|EffectParam $reshape The reshape to apply.
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
     * All parameters specified: Each corner is rounded accordingly.
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
        $params = ArrayUtils::safeFilter([$radiusOrTopLeft, $topRight, $bottomRight, $bottomLeft]);

        $roundCorners = ClassUtils::verifyVarArgsInstance($params, RoundCorners::class);

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
     * @param Background|string $background
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\Border
     */
    public function background($background)
    {
        return $this->addAction(ClassUtils::verifyInstance($background, Background::class));
    }

    /**
     * Uses the specified public ID of a placeholder image if the requested image or social network picture does
     * not exist. The name of the placeholder image must include the file extension.
     *
     * @param DefaultImage|string $defaultImage The public ID of the placeholder image.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\DefaultImage
     */
    public function defaultImage($defaultImage)
    {
        return $this->addAction(ClassUtils::verifyInstance($defaultImage, DefaultImage::class));
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
}
