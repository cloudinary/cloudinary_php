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
class Shadow extends ColoredEffectAction
{
    const SHADOW = 'shadow';

    use AbsolutePositionTrait; // FIXME: use direction/relative position

    /**
     * Shadow constructor.
     *
     * @param      $strength
     * @param null $position
     * @param null $color
     */
    public function __construct($strength = null, $position = null, $color = null)
    {
        parent::__construct(self::SHADOW, $strength);

        $this->position($position);//FIXME:: x and y
        $this->color($color);
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
        if (! isset($this->parameters[AbsolutePosition::getName()])) {
            $this->addParameter(Position::absolute());
        }

        $this->parameters[AbsolutePosition::getName()]->setPointValue($value);

        return $this;
    }
}
