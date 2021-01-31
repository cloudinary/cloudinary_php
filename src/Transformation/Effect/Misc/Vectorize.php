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
 * Class Vectorize
 *
 * @api
 */
class Vectorize extends EffectQualifier
{
    const VALUE_CLASS = VectorizeValue::class;

    use VectorizeTrait;

    /**
     * Vectorize constructor.
     *
     * @param int   $colors    The number of colors. (Range: 2 to 30, Server default: 10)
     * @param float $detail    The level of detail. Specify either a percentage of the original image (Range: 0.0 to
     *                         1.0) or an absolute number of pixels (Range: 0 to 1000). (Server default: 300)
     * @param float $despeckle The size of speckles to suppress. Specify either a percentage of the original image
     *                         (Range: 0.0 to 1.0) or an absolute number of pixels (Range: 0 to 100, Server default: 2)
     * @param int   $corners   The corner threshold.  Specify 100 for no smoothing (polygon corners), 0 for completely
     *                         smooth corners. (Range: 0 to 100, Default: 25)
     * @param int   $paths     The optimization value. Specify 100 for least optimization and the largest file.
     *                         (Range: 0 to 100, Server default: 100).
     */
    public function __construct($colors = null, $detail = null, $despeckle = null, $corners = null, $paths = null)
    {
        parent::__construct(null);

        $this->numOfColors($colors);
        $this->detailsLevel($detail);
        $this->despeckleLevel($despeckle);
        $this->cornersLevel($corners);
        $this->paths($paths);
    }

    /**
     * Sets a simple named value specified by name (for uniqueness) and the actual value.
     *
     * @param string              $name  The name of the argument.
     * @param BaseComponent|mixed $value The value of the argument.
     *
     * @return $this
     *
     * @internal
     */
    protected function setSimpleNamedValue($name, $value)
    {
        $this->value->setSimpleNamedValue($name, $value);

        return $this;
    }
}
