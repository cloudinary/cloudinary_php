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
use Cloudinary\Transformation\Qualifier\BaseExpressionQualifier;

/**
 * Class EffectQualifier
 */
class EffectQualifier extends BaseExpressionQualifier
{
    /**
     * @var string $key Serialization key, force it here for derived classes usage
     */
    protected static $key = 'e';

    /**
     * @var string $name Serialization name, force it here for derived classes usage
     */
    protected static $name = 'effect';

    /**
     * EffectQualifier constructor.
     *
     * @param string $effectName The name of the effect.
     * @param mixed  ...$values  The effect values.
     */
    public function __construct($effectName, ...$values)
    {
        parent::__construct(ClassUtils::verifyInstance($effectName, EffectName::class), ...$values);
    }

    /**
     * Sets (overrides) the name of the effect.
     *
     * @param string|EffectName $effectName The effect name.
     *
     * @return EffectQualifier
     */
    public function setEffectName($effectName)
    {
        $this->getValue()->setSimpleValue(0, ClassUtils::verifyInstance($effectName, EffectName::class));

        return $this;
    }
}
