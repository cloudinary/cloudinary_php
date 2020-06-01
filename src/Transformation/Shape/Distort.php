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

use Cloudinary\Transformation\Argument\PointValue;

/**
 * Class DistortArc
 */
class Distort extends EffectParam
{
    /**
     * DistortArc constructor.
     *
     * @param PointValue $topLeft
     * @param PointValue $topRight
     * @param PointValue $bottomRight
     * @param PointValue $bottomLeft
     */
    public function __construct(
        PointValue $topLeft = null,
        PointValue $topRight = null,
        PointValue $bottomRight = null,
        PointValue $bottomLeft = null
    ) {
        parent::__construct(ReshapeParam::DISTORT);

        $this->add($topLeft, $topRight, $bottomRight, $bottomLeft);
    }
}
