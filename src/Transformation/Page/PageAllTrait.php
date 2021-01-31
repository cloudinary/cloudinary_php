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
 * Trait PageAllTrait
 *
 * @api
 */
trait PageAllTrait
{
    /**
     * Gets all pages.
     *
     * @return mixed
     */
    public function all()
    {
        return $this->add('all');
    }
}
