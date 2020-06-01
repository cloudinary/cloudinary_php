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

/**
 * Class RoundCorners
 *
 * @api
 */
class RoundCorners extends BaseAction
{
    use CornerRadiusTrait;
    use CornersTrait;

    /**
     * RoundCorners constructor.
     *
     * @param mixed ...$parameters
     */
    public function __construct(...$parameters)
    {
        parent::__construct(ClassUtils::verifyVarArgsInstance($parameters, CornerRadius::class));
    }

    /**
     * Sets the corner radius.
     *
     * @param int|string|array|mixed $values The corner(s) radius.
     *
     * @return static
     */
    public function setRadius(...$values)
    {
        return $this->addParameter(ClassUtils::verifyVarArgsInstance($values, CornerRadius::class));
    }

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
        $this->parameters[CornerRadius::getName()]->setSimpleValue($name, $value);

        return $this;
    }

    /**
     * Internal named constructor.
     *
     * @param int|string|array|mixed $radius The corner(s) radius.
     *
     * @return static
     *
     * @internal
     */
    protected static function createWithRadius(...$radius)
    {
        return new static(CornerRadius::radius(...$radius));
    }
}
