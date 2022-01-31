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
 * Trait PageIndexTrait
 *
 * @api
 */
trait PageIndexTrait
{
    /**
     * Creates and instance using the index.
     *
     * @param int $index The index.
     *
     * @return static
     */
    public function byIndex($index)
    {
        $this->add($index);

        return $this;
    }
}
