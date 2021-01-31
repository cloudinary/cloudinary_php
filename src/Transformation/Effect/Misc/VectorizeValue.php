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
 * Class VectorizeValue
 */
class VectorizeValue extends QualifierMultiValue
{
    use VectorizeTrait;

    const COLORS    = 'colors';
    const DETAIL    = 'detail';
    const DESPECKLE = 'despeckle';
    const PATHS     = 'paths';
    const CORNERS   = 'corners';

    const KEY_VALUE_DELIMITER = ':';

    const COLOR_RANGE            = [2, 30];
    const DETAIL_RANGE           = [0, 1000];
    const PATHS_RANGE            = [0, 100];
    const CORNER_THRESHOLD_RANGE = [0, 100];

    /**
     * VectorizeValue constructor.
     *
     * @param null $colors
     * @param null $detail
     * @param null $despeckle
     * @param null $paths
     * @param null $corners
     */
    public function __construct($colors = null, $detail = null, $despeckle = null, $paths = null, $corners = null)
    {
        parent::__construct(MiscEffect::VECTORIZE);

        $this->numOfColors($colors);
        $this->detailsLevel($detail);
        $this->despeckleLevel($despeckle);
        $this->paths($paths);
        $this->cornersLevel($corners);
    }
}
