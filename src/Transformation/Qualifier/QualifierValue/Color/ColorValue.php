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

use Cloudinary\Transformation\Qualifier\BaseQualifier;
use Cloudinary\Transformation\QualifierMultiValue;

/**
 * Class ColorValue
 */
class ColorValue extends QualifierMultiValue
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
     * @param string|ColorValue|QualifierMultiValue|BaseQualifier $color The color.
     *
     * @return $this
     */
    public function color($color)
    {
        if ($color instanceof BaseQualifier) {
            $color = $color->getValue(); // for those who accidentally pass ColorQualifier instead of the value
        }

        $this->setSimpleValue('color', preg_replace('/^#/', 'rgb:', (string)$color));

        return $this;
    }
}
