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

use Cloudinary\Transformation\Argument\RangeArgument;

/**
 * Trait PageRangeTrait
 *
 * @api
 */
trait PageRangeTrait
{
    /**
     * Gets pages using the specified range.
     *
     * @param int|string $start The start of the range.
     * @param int|string $end   The end of the range.
     *
     * @return static
     */
    public function byRange($start, $end = null)
    {
        $this->add(new RangeArgument($start, $end));

        return $this;
    }
}
