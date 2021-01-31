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
 * Class GenericResize
 */
class GenericResize extends BaseResizeAction
{
    use CompassPositionTrait;
    use BackgroundTrait;
    use GenericResizeTrait;

    //TODO: discuss other traits that can be put here

    /**
     * Internal setter for offset.
     *
     * @param Offset $value The offset
     *
     * @return $this
     *
     * @internal
     */
    public function setOffsetValue($value)
    {
        if (! isset($this->qualifiers[CompassPosition::getName()])) {
            $this->addQualifier(new CompassPosition());
        }

        $this->qualifiers[CompassPosition::getName()]->setOffsetValue($value);

        return $this;
    }
}
