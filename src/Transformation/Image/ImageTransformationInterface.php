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
 * Interface ImageTransformationInterface
 */
interface ImageTransformationInterface extends CommonTransformationInterface
{
    /**
     * Applies a filter or an effect on an image.
     *
     * @param EffectQualifier|EffectAction $effect The effect to apply.
     *
     * @return static
     *
     * @see \Cloudinary\Transformation\EffectAction
     * @see \Cloudinary\Transformation\EffectQualifier
     */
    public function effect($effect);

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
    public function overlay($layer, $position = null, $blendMode = null);

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
    public function underlay($layer, $position = null, $blendMode = null);

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
    public function border(Border $border);
}
