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
 * Class SmartObjectParam
 */
class SmartObjectParam extends PageParam
{
    /**
     * SmartObjectParam constructor.
     *
     * @param $value
     */
    public function __construct(...$value)
    {
        parent::__construct(new SmartObjectName(...self::collectValues($value))); // FIXME: improve parameter handling
    }
}
