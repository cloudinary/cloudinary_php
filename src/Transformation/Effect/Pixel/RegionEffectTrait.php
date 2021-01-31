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

/**
 * Class RegionEffectTrait
 */
trait RegionEffectTrait
{
    use RegionTrait;

    /**
     * Internal setter for the point value.
     *
     * @param mixed $value
     *
     * @return static
     *
     * @internal
     */
    public function setPointValue($value)
    {
        if (! isset($this->qualifiers[Region::getName()])) {
            $this->addQualifier(new Region());
        }

        $this->qualifiers[Region::getName()]->setPointValue($value);

        return $this;
    }

    /**
     * Internal setter for the dimensions.
     *
     * @param Region|mixed $value The dimensions.
     *
     * @return static
     *
     * @internal
     */
    protected function setDimension($value)
    {
        if (! isset($this->qualifiers[Region::getName()])) {
            $this->addQualifier(new Region());
        }

        $this->qualifiers[Region::getName()]->setDimension($value);

        return $this;
    }

    /**
     * Collects and flattens action qualifiers.
     *
     * Here comes the magic. We have different effect names based on the region.
     * That's where we alter qualifiers without affection action state.
     *
     * @return array A flat array of qualifiers
     *
     * @internal
     */
    public function getStringQualifiers()
    {
        $actionQualifiers = $this->qualifiers;

        if (isset($actionQualifiers[Region::getName()])) {
            $regionType = $actionQualifiers[Region::getName()]->getRegionType();
            if ($regionType === Region::FACES) {
                $actionQualifiers[EffectQualifier::getName()]->setEffectName(static::FACES);
                unset($actionQualifiers[Region::getName()]); // no region for faces
            } else {
                $actionQualifiers[EffectQualifier::getName()]->setEffectName(static::REGION);
            }
        }

        $flatQualifierTraits = [];

        foreach ($actionQualifiers as $qualifier) {
            $flatQualifierTraits = ArrayUtils::mergeNonEmpty($flatQualifierTraits, $qualifier->getStringQualifiers());
        }

        return $flatQualifierTraits;
    }
}
