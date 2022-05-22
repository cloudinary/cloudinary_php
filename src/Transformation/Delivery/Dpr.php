<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Qualifier\Dimensions;

use Cloudinary\Transformation\Qualifier\BaseExpressionQualifier;

/**
 * Class Dpr
 *
 * @api
 */
class Dpr extends BaseExpressionQualifier
{
    /**
     * @var string $key Serialization key.
     */
    protected static $key = 'dpr';

    const AUTO = 'auto';

    /**
     * @return static
     */
    public static function auto()
    {
        return new static(static::AUTO);
    }
}
