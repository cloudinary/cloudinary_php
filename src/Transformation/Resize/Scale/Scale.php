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
     * @return $this
     */
    public function liquidRescaling()
    {
        /** @noinspection TypeUnsafeComparisonInspection */
        if ($this->parameters[CropMode::getName()]->getValue() != CropMode::SCALE) {
            throw new InvalidArgumentException(
                "Liquid Rescaling is not supported for {$this->parameters[CropMode::getName()]->getValue()}"
            );
        }

        $this->addParameter(new LiquidRescaling());

        return $this;
    }
}
