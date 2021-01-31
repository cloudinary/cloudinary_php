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
 * Class Shadow
 */
class Shadow extends StrengthEffectAction
{
    const SHADOW = 'shadow';

    use ColorTrait;
    use OffsetTrait;

    /**
     * Shadow constructor.
     *
     * @param        $strength
     * @param null   $offsetX
     * @param null   $offsetY
     * @param null   $color
     */
    public function __construct($strength = null, $offsetX = null, $offsetY = null, $color = null)
    {
        parent::__construct(new StrengthEffectQualifier(self::SHADOW, EffectRange::PERCENT));
        $this->strength($strength);
        $this->offset($offsetX, $offsetY);
        $this->color($color);
    }

    /**
     * @param $value
     *
     * @return static
     * @internal
     *
     */
    public function setOffsetValue($value)
    {
        $this->addQualifier($value);

        return $this;
    }
}
