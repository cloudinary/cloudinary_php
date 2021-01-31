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
 * Applies a complex deep learning neural network algorithm that extracts artistic styles from a source image
 * and applies them to the content of a target photograph.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/documentation/neural_artwork_style_transfer_addon" target="_blank">
 * Neural Artwork Style Transfer</a>
 *
 * @api
 */
class StyleTransfer extends SourceBasedEffectAction
{
    /**
     * StyleTransfer constructor.
     *
     * @param String|Media $source        The public ID of the source artwork.
     * @param int          $strength      Sets the strength of the style transfer.
     * @param bool         $preserveColor Determines whether the original colors of the target photo are kept.
     */
    public function __construct($source, $strength = null, $preserveColor = null)
    {
        parent::__construct(new StyleTransferQualifier($strength, $preserveColor));

        $this->source($source);
    }

    /**
     * Determines whether the original colors of the target photo are kept.
     *
     * @param bool $preserveColor   When true, style elements of the source artwork, such as brush style and texture,
     *                              are transferred to the target photo, but the prominent colors from the source
     *                              artwork are not transferred, so the result retains the original colors of the
     *                              target photo.
     *
     * @return StyleTransfer
     */
    public function preserveColor($preserveColor = true)
    {
        $this->qualifiers[StyleTransferQualifier::getName()]->preserveColor($preserveColor);

        return $this;
    }

    /**
     * Sets the strength of the style transfer.
     *
     * @param int $strength         The strength of the style transfer. Higher numbers result in an output that is more
     *                              highly influenced by the source artwork style. (Range: 0 to 100, Server default:
     *                              100)
     *
     * @return StyleTransfer
     */
    public function strength($strength)
    {
        $this->qualifiers[StyleTransferQualifier::getName()]->strength($strength);

        return $this;
    }
}
