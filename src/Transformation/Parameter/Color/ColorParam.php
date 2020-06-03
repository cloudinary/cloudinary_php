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
use Cloudinary\Transformation\Argument\ColorTrait as ParamColorTrait; # Fix PHP5.6 conflict
use Cloudinary\Transformation\Parameter\BaseParameter;
use Cloudinary\Transformation\Parameter\Value\ColorValueTrait;

/**
 * Class Color
 */
class ColorParam extends BaseParameter
{
    use ParamColorTrait;
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
