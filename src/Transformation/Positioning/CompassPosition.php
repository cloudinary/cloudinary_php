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
 * Class CompassPosition
 */
class CompassPosition extends BasePosition
{
    use CompassPositionTrait;

    /**
     * CompassPosition constructor.
     *
     * @param null $gravity
     * @param null $x Offset x
     * @param null $y Offset y
     */
    public function __construct($gravity = null, $x = null, $y = null)
    {
        parent::__construct();

        $this->gravity($gravity);
        $this->offset($x, $y);
    }

    /**
     * @internal
     *
     * @param $value
     *
     * @return static
     */
    public function setOffsetValue($value)
    {
        if (! isset($this->qualifiers[Offset::getName()])) {
            $this->addQualifier(new Offset());
        }

        $this->qualifiers[Offset::getName()]->addQualifier($value);

        return $this;
    }
}
