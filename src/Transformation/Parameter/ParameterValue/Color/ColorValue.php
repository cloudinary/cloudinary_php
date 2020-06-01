<?php
/**
 * This file is part of the Cloudinary PHP package.
 *
 * (c) Cloudinary
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Cloudinary\Transformation\Argument;

use Cloudinary\Transformation\Parameter\BaseParameter;
use Cloudinary\Transformation\ParameterMultiValue;

/**
 * Class ColorValue
 */
class ColorValue extends ParameterMultiValue
{
    use ColorTrait;

    /**
     * ColorValue constructor.
     *
     * @param string $color The color. Can be RGB, HEX, named color, etc.
     */
    public function __construct($color)
    {
        parent::__construct();

        $this->color($color);
    }

    /**
     * Sets the color.
     *
     * @param string|ColorValue|ParameterMultiValue|BaseParameter $color The color.
     *
     * @return $this
     */
    public function color($color)
    {
        if ($color instanceof BaseParameter) {
            $color = $color->getValue(); // for those who accidentally pass ColorParam parameter instead of the value
        }

        $this->setSimpleValue('color', preg_replace('/^#/', 'rgb:', (string)$color));

        return $this;
    }
}
