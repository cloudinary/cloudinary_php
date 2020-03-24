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
use Cloudinary\Transformation\Argument\NamedColorTrait;
use Cloudinary\Transformation\Parameter\BaseParameter;
use Cloudinary\Transformation\Parameter\Value\ColorValueTrait;

/**
 * Class Background
 */
class Background extends BaseParameter
{
    use NamedColorTrait;
    use ColorValueTrait;

    /**
     * Background constructor.
     *
     * @param $color
     */
    public function __construct($color)
    {
        parent::__construct(ClassUtils::verifyInstance($color, ColorValue::class));
    }
}
