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
use Cloudinary\Asset\Media;

/**
 * Trims pixels according to the transparency levels of a specified overlay image.
 *
 * Wherever an overlay image is transparent, the original is shown, and wherever an overlay is opaque, the resulting
 * image is transparent.
 *
 * **Learn more**: <a
 * href="https://cloudinary.com/cookbook/custom_shapes_cut_out" target="_blank">
 * Custom shapes cut out</a>
 *
 * @api
 */
class CutOut extends SourceBasedEffectAction
{
    /**
     * CutOut constructor.
     *
     * @param string|Media $source The public ID of the source.
     */
    public function __construct($source)
    {
        parent::__construct(new EffectQualifier(PixelEffect::CUT_OUT));

        $this->source($source);
    }

    /**
     * This function is similar to SourceBasedEffectAction::getSubActionQualifiers.
     *
     * The difference is that additional qualifiers come with the source sub-action.
     *
     * For example:
     *  e_cut_out,l_logo/fl_layer_apply,g_south,y_20
     *
     * Note that e_cut_out comes with l_logo in the same sub-action.
     *
     * @return array
     *
     * @see BaseSourceContainer::getSubActionQualifiers
     */
    protected function getSubActionQualifiers()
    {
        $sourceQualifiers     = $this->source ? $this->source->getStringQualifiers() : [];
        $sourceTransformation = $this->source ? $this->source->getTransformation() : null;
        $positionQualifiers   = $this->position ? $this->position->getStringQualifiers() : [];
        $additionalQualifiers = $this->getStringQualifiers();

        return [
            'source'         => ArrayUtils::mergeNonEmpty($sourceQualifiers, $additionalQualifiers),
            'transformation' => $sourceTransformation,
            'additional'     => ArrayUtils::mergeNonEmpty($positionQualifiers, [Flag::layerApply()]),
        ];
    }
}
