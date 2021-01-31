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
 * Class RegionEffectAction
 */
class RegionEffectAction extends EffectAction
{
    use RegionTrait;

    /**
     * RegionEffectAction constructor.
     *
     * @param       $effectName
     * @param       $range
     * @param       $strength
     * @param null  $region
     * @param mixed ...$args
     */
    public function __construct($effectName, $range, $strength = null, $region = null, ...$args)
    {
        parent::__construct(
            new LimitedEffectQualifier($effectName, $range, $strength),
            ...$args
        );

        $this->region($region);
    }

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
}
