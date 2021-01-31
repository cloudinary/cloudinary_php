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

use Cloudinary\ClassUtils;
use Cloudinary\Transformation\Argument\ColorValue;
use Cloudinary\Transformation\Argument\ColorTrait as QualifierColorTrait; # Fix PHP5.6 conflict
use Cloudinary\Transformation\Qualifier\BaseQualifier;
use Cloudinary\Transformation\Qualifier\Value\ColorValueTrait;

/**
 * Class Color
 */
class ColorQualifier extends BaseQualifier
{
    use QualifierColorTrait;
    use ColorValueTrait;

    /**
     * @var string $key Serialization key.
     */
    protected static $key = 'co';

    /**
     * Color constructor.
     *
     * @param $color
     */
    public function __construct($color)
    {
        parent::__construct(ClassUtils::verifyInstance($color, ColorValue::class));
    }
}
