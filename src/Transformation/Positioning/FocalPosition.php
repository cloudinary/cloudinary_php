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
 * Class FocalPosition
 */
class FocalPosition extends BasePosition
{
    use FocalGravityTrait;

    /**
     * FocalPosition constructor.
     *
     * @param FocalGravity $gravity
     */
    public function __construct(FocalGravity $gravity = null)
    {
        parent::__construct();

        $this->gravity($gravity);
    }
}
