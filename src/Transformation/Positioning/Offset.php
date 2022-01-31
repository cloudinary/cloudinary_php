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
 * Class Offset
 */
class Offset extends BaseAction
{
    use OffsetTrait;

    /**
     * Offset constructor.
     *
     * @param null $x
     * @param null $y
     */
    public function __construct($x = null, $y = null)
    {
        parent::__construct();

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
        return $this->addQualifier($value);
    }
}
