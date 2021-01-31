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
 * Trait PageNumberTrait
 *
 * @api
 */
trait PageNumberTrait
{
    /**
     * Gets the page using the specified number.
     *
     * @param int $number The number.
     *
     * @return static
     */
    public function byNumber($number)
    {
        $this->add($number);

        return $this;
    }
}
