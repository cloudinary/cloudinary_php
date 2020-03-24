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
 * Class Vectorize
 */
class Vectorize extends EffectParam
{
    const VALUE_CLASS = VectorizeValue::class;

    use VectorizeTrait;

    /**
     * Vectorize constructor.
     *
     * @param mixed ...$arguments
     */
    public function __construct(...$arguments)
    {
        parent::__construct(MiscEffect::VECTORIZE, ...$arguments);
    }

    /**
     * Sets a simple named value specified by name (for uniqueness) and the actual value.
     *
     * @param string              $name  The name of the argument.
     * @param BaseComponent|mixed $value The value of the argument.
     *
     * @return $this
     *
     * @internal
     */
    protected function setSimpleNamedValue($name, $value)
    {
        $this->value->setSimpleNamedValue($name, $value);

        return $this;
    }
}
