<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Parameter\VideoRange;

/**
 * Class StartOffset
 */
class StartOffset extends BaseOffsetParameter
{
    /**
     * @var bool Indicates whether to allow value 'auto'.
     */
    protected static $allowAuto = true;
}
