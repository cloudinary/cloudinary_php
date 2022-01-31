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

use InvalidArgumentException;

/**
 * Class Scale
 */
class Scale extends BaseResizeAction
{
    use ScaleTrait;

    /**
     * Changes the aspect ratio of an image while retaining all important content and avoiding unnatural distortions.
     *
     * Liquid Rescaling is only supported for Scale mode.
     *
     * @return $this
     *
     * @see \Cloudinary\Transformation\LiquidRescaling
     */
    public function liquidRescaling()
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($this->qualifiers[CropMode::getName()]->getValue() != CropMode::SCALE) {
            throw new InvalidArgumentException(
                "Liquid Rescaling is not supported for {$this->qualifiers[CropMode::getName()]->getValue()}"
            );
        }

        $this->addQualifier(new LiquidRescaling());

        return $this;
    }
}
