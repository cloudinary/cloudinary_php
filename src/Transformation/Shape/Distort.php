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
class Distort extends EffectQualifier
{
    /**
     * DistortArc constructor.
     *
     * @param PointValue|array|int|string $topLeft
     * @param PointValue|array|int|string $topRight
     * @param PointValue|array|int|string $bottomRight
     * @param PointValue|array|int|string $bottomLeft
     * @param mixed                       ...$args Additional arguments
     */
    public function __construct(
        $topLeft = null,
        $topRight = null,
        $bottomRight = null,
        $bottomLeft = null,
        ...$args
    ) {
        parent::__construct(ReshapeQualifier::DISTORT);

        $this->add($topLeft, $topRight, $bottomRight, $bottomLeft, ...$args);
    }
}
