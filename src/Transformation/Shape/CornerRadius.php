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

use Cloudinary\Transformation\Qualifier\BaseQualifier;

/**
 * Class CornerRadius
 */
class CornerRadius extends BaseQualifier
{
    const VALUE_CLASS = Corners::class;

    use CornerRadiusTrait;
    use CornersTrait;

    const MAX = 'max';

    /**
     * @var string $key Serialization key.
     */
    protected static $key = 'r';

    /**
     * Sets a simple unnamed value specified by name (for uniqueness) and the actual value.
     *
     * @param string              $name  The name of the argument.
     * @param BaseComponent|mixed $value The value of the argument.
     *
     * @return $this
     *
     * @internal
     */
    public function setSimpleValue($name, $value = null)
    {
        $this->value->setSimpleValue($name, $value);

        return $this;
    }
}
